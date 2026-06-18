import { AnimatePresence, motion } from 'motion/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import { BrandIcon } from '../shared';

const DEFAULTS = {
    email: 'bimoalfarrabi24@gmail.com',
    available: true,
    availableLabel: 'Available for new projects',
    busyLabel: 'Booked, but still reading messages',
    location: 'Indonesia',
    timeZone: 'Asia/Jakarta',
    timeZoneLabel: 'GMT+7',
    responseTime: 'Usually replies within 24h',
    channels: [
        { label: 'LinkedIn', href: 'https://linkedin.com/in/bimoalfarrabi', handle: 'in/bimoalfarrabi' },
        { label: 'GitHub', href: 'https://github.com/bimoalfarrabi', handle: '@bimoalfarrabi' },
    ],
};

function normalizeCollab(raw) {
    if (!raw || typeof raw !== 'object') return DEFAULTS;
    const channels = Array.isArray(raw.channels) && raw.channels.length > 0
        ? raw.channels.map((channel) => ({
            label: channel.label ?? '',
            href: channel.href ?? '#',
            handle: channel.handle ?? '',
        }))
        : DEFAULTS.channels;

    return {
        email: raw.email ?? DEFAULTS.email,
        available: raw.available ?? DEFAULTS.available,
        availableLabel: raw.available_label ?? raw.availableLabel ?? DEFAULTS.availableLabel,
        busyLabel: raw.busy_label ?? raw.busyLabel ?? DEFAULTS.busyLabel,
        location: raw.location ?? DEFAULTS.location,
        timeZone: raw.time_zone ?? raw.timeZone ?? DEFAULTS.timeZone,
        timeZoneLabel: raw.time_zone_label ?? raw.timeZoneLabel ?? DEFAULTS.timeZoneLabel,
        responseTime: raw.response_time ?? raw.responseTime ?? DEFAULTS.responseTime,
        channels,
    };
}

function useLocalClock(timeZone) {
    const [clock, setClock] = useState({ time: '', date: '', dayProgress: 0 });

    useEffect(() => {
        const build = (tz) => {
            const opts = tz ? { timeZone: tz } : {};
            return {
                time: new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false, ...opts }),
                date: new Intl.DateTimeFormat('en-GB', { weekday: 'short', day: '2-digit', month: 'short', ...opts }),
                parts: new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, ...opts }),
            };
        };

        let formatters;
        try {
            formatters = build(timeZone);
        } catch {
            formatters = build(null);
        }

        const tick = () => {
            const now = new Date();
            const parts = formatters.parts.formatToParts(now);
            const read = (type) => Number(parts.find((p) => p.type === type)?.value ?? 0);
            const elapsed = read('hour') * 3600 + read('minute') * 60 + read('second');
            setClock({
                time: formatters.time.format(now),
                date: formatters.date.format(now),
                dayProgress: Math.min(1, elapsed / 86400),
            });
        };
        tick();
        const handle = window.setInterval(tick, 1000 * 30);
        return () => window.clearInterval(handle);
    }, [timeZone]);

    return clock;
}

export default function Collab({ collab }) {
    const profile = useMemo(() => normalizeCollab(collab), [collab]);
    const [copied, setCopied] = useState(false);
    const { time: localTime, date: localDate, dayProgress } = useLocalClock(profile.timeZone);

    const [formOpen, setFormOpen] = useState(false);
    const [form, setForm] = useState({ name: '', email: '', message: '', company: '' });
    const [formState, setFormState] = useState('idle');
    const [feedback, setFeedback] = useState('');
    const [fieldErrors, setFieldErrors] = useState({});
    const mountedAtRef = useRef(Date.now());

    useEffect(() => {
        if (!copied) return undefined;
        const handle = window.setTimeout(() => setCopied(false), 2000);
        return () => window.clearTimeout(handle);
    }, [copied]);

    useEffect(() => {
        if (formOpen) mountedAtRef.current = Date.now();
    }, [formOpen]);

    const handleCopy = async () => {
        try {
            await navigator.clipboard.writeText(profile.email);
            setCopied(true);
        } catch {
            setCopied(false);
        }
    };

    const updateField = (key) => (event) => setForm((prev) => ({ ...prev, [key]: event.target.value }));

    const handleSubmit = async (event) => {
        event.preventDefault();
        if (formState === 'sending') return;

        setFormState('sending');
        setFeedback('');
        setFieldErrors({});

        const elapsed = Math.round((Date.now() - mountedAtRef.current) / 1000);

        try {
            const response = await window.axios.post('/collab/messages', {
                name: form.name,
                email: form.email,
                message: form.message,
                company: form.company,
                elapsed,
            });
            setFormState('success');
            setFeedback(response?.data?.message ?? 'Pesan terkirim. Terima kasih.');
            setForm({ name: '', email: '', message: '', company: '' });
        } catch (error) {
            const data = error?.response?.data;
            setFieldErrors(data?.errors ?? {});
            setFeedback(data?.message ?? 'Gagal mengirim pesan. Coba lagi nanti.');
            setFormState('error');
        }
    };

    const statusLabel = profile.available ? profile.availableLabel : profile.busyLabel;
    const inputClass = (hasError) =>
        `border bg-surface-1 px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-ink ${hasError ? 'border-accent' : 'border-line'}`;

    return (
        <section id="collab" className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink">
            <div className="pointer-events-none absolute inset-0 grid-hairline-soft opacity-50" />

            <div className="relative mx-auto max-w-6xl">
                {/* Header */}
                <motion.div
                    data-reveal
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                    className="mb-12 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"
                >
                    <div>
                        <p className="eng-label mb-3">SYS_COMMS · 08</p>
                        <h2 className="max-w-3xl text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
                            Got a signal
                            <span className="block text-ink-faint">worth <span className="text-accent">sending</span>?</span>
                        </h2>
                    </div>
                    <p className="max-w-sm text-sm leading-7 text-ink-mute">
                        Punya project, kolaborasi, atau ide yang ingin diuji? Kirim sinyalnya. Saya terbuka untuk obrolan yang menarik.
                    </p>
                </motion.div>

                {/* Card grid */}
                <div className="grid gap-px border border-line bg-line lg:grid-cols-3">
                    {/* Email — primary */}
                    <motion.div
                        initial={{ y: 18 }}
                        whileInView={{ y: 0 }}
                        viewport={{ once: true, margin: '-80px' }}
                        transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
                        className="bg-surface-1 p-6 sm:p-8 lg:col-span-2"
                    >
                        <div className="mb-8 flex items-center justify-between">
                            <span className="eng-label">CH_01 // EMAIL</span>
                            <span className="inline-flex items-center gap-2 border border-line bg-surface-2 px-3 py-1 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-soft">
                                <span className="relative flex size-2 items-center justify-center">
                                    {profile.available && (
                                        <span className="absolute inset-0 rounded-full bg-success/40 starfield-blip" />
                                    )}
                                    <span className={`relative size-1.5 rounded-full ${profile.available ? 'bg-success' : 'bg-ink-faint'}`} />
                                </span>
                                {statusLabel}
                            </span>
                        </div>

                        <a
                            href={`mailto:${profile.email}`}
                            className="inline-block break-all text-[clamp(1.5rem,3.4vw,2.4rem)] font-semibold tracking-[-0.03em] text-ink transition-colors duration-200 hover:text-accent"
                        >
                            {profile.email}
                        </a>

                        <div className="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a
                                href={`mailto:${profile.email}`}
                                className="inline-flex items-center justify-center gap-2 bg-ink px-7 py-3.5 text-sm font-medium uppercase tracking-[0.16em] text-surface-1 transition-colors duration-200 hover:bg-accent"
                            >
                                <span className="font-mono text-xs">[TX]</span>
                                Send a message
                            </a>
                            <button
                                type="button"
                                onClick={handleCopy}
                                className="inline-flex items-center justify-center gap-2 border border-line bg-surface-1 px-7 py-3.5 text-sm font-medium uppercase tracking-[0.16em] text-ink-soft transition-colors duration-200 hover:border-ink hover:text-ink"
                            >
                                {copied ? 'Copied' : 'Copy email'}
                            </button>
                            <button
                                type="button"
                                onClick={() => setFormOpen((open) => !open)}
                                className="inline-flex items-center justify-center gap-2 border border-line bg-surface-1 px-7 py-3.5 text-sm font-medium uppercase tracking-[0.16em] text-ink-soft transition-colors duration-200 hover:border-ink hover:text-ink"
                                aria-expanded={formOpen}
                                aria-controls="collab-form"
                            >
                                {formOpen ? 'Close form' : 'Use form'}
                            </button>
                        </div>
                        {profile.responseTime && (
                            <p className="mt-4 eng-label">{profile.responseTime}</p>
                        )}

                        <AnimatePresence initial={false}>
                            {formOpen && (
                                <motion.form
                                    key="collab-form"
                                    id="collab-form"
                                    onSubmit={handleSubmit}
                                    initial={{ height: 0, opacity: 0 }}
                                    animate={{
                                        height: 'auto',
                                        opacity: 1,
                                        transition: {
                                            height: { duration: 0.42, ease: [0.16, 1, 0.3, 1] },
                                            opacity: { duration: 0.28, ease: 'easeOut', delay: 0.08 },
                                        },
                                    }}
                                    exit={{
                                        height: 0,
                                        opacity: 0,
                                        transition: {
                                            height: { duration: 0.3, ease: [0.7, 0, 0.84, 0] },
                                            opacity: { duration: 0.18, ease: 'easeIn' },
                                        },
                                    }}
                                    className="overflow-hidden"
                                    noValidate
                                >
                                    <div className="mt-8 border border-line bg-surface-2 p-5 sm:p-6">
                                        <p className="eng-label mb-4">TRANSMISSION_FORM</p>

                                        <div className="grid gap-3 sm:grid-cols-2">
                                            <label className="grid gap-1.5">
                                                <span className="eng-label">Name</span>
                                                <input
                                                    type="text"
                                                    name="name"
                                                    autoComplete="name"
                                                    value={form.name}
                                                    onChange={updateField('name')}
                                                    required
                                                    maxLength={120}
                                                    className={inputClass(fieldErrors.name)}
                                                    placeholder="Siapa yang mengirim?"
                                                />
                                                {fieldErrors.name && <span className="text-xs text-accent">{fieldErrors.name[0]}</span>}
                                            </label>
                                            <label className="grid gap-1.5">
                                                <span className="eng-label">Email</span>
                                                <input
                                                    type="email"
                                                    name="email"
                                                    autoComplete="email"
                                                    value={form.email}
                                                    onChange={updateField('email')}
                                                    required
                                                    maxLength={255}
                                                    className={inputClass(fieldErrors.email)}
                                                    placeholder="nama@email.com"
                                                />
                                                {fieldErrors.email && <span className="text-xs text-accent">{fieldErrors.email[0]}</span>}
                                            </label>
                                        </div>

                                        <label className="mt-3 grid gap-1.5">
                                            <span className="eng-label">Message</span>
                                            <textarea
                                                name="message"
                                                rows={5}
                                                value={form.message}
                                                onChange={updateField('message')}
                                                required
                                                minLength={10}
                                                maxLength={4000}
                                                className={inputClass(fieldErrors.message)}
                                                placeholder="Cerita singkat soal project, timeline, atau ide yang ingin dibahas."
                                            />
                                            {fieldErrors.message && <span className="text-xs text-accent">{fieldErrors.message[0]}</span>}
                                        </label>

                                        {/* Honeypot */}
                                        <div aria-hidden="true" className="absolute -left-[9999px] h-0 w-0 overflow-hidden">
                                            <label>
                                                Company
                                                <input type="text" name="company" tabIndex={-1} autoComplete="off" value={form.company} onChange={updateField('company')} />
                                            </label>
                                        </div>

                                        <div className="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
                                            <button
                                                type="submit"
                                                disabled={formState === 'sending'}
                                                className="inline-flex items-center justify-center gap-2 bg-ink px-6 py-3 text-sm font-medium uppercase tracking-[0.16em] text-surface-1 transition-colors duration-200 hover:bg-accent disabled:cursor-not-allowed disabled:opacity-60"
                                            >
                                                {formState === 'sending' ? 'Transmitting...' : 'Transmit'}
                                            </button>
                                            <p className="eng-label">Sent to my email + logged in Mission Control.</p>
                                        </div>

                                        {feedback && (
                                            <p
                                                role="status"
                                                className={`mt-4 border px-4 py-3 text-sm ${formState === 'success' ? 'border-success/50 bg-success-soft/40 text-success-deep' : 'border-warn/50 bg-warn-soft/40 text-warn-deep'}`}
                                            >
                                                {feedback}
                                            </p>
                                        )}
                                    </div>
                                </motion.form>
                            )}
                        </AnimatePresence>
                    </motion.div>

                    {/* Local signal */}
                    <motion.div
                        initial={{ y: 18 }}
                        whileInView={{ y: 0 }}
                        viewport={{ once: true, margin: '-80px' }}
                        transition={{ duration: 0.5, delay: 0.08, ease: [0.16, 1, 0.3, 1] }}
                        className="bg-surface-1 p-6 sm:p-8"
                    >
                        <div className="mb-8 flex items-center justify-between">
                            <span className="eng-label">LOCAL_SIGNAL</span>
                            <span className="size-1.5 rounded-full bg-accent" />
                        </div>

                        <p className="font-mono text-4xl tracking-[-0.04em] text-ink tabular-nums">
                            {localTime || '--:--'}
                            {profile.timeZoneLabel && (
                                <span className="ml-2 text-base text-ink-mute">{profile.timeZoneLabel}</span>
                            )}
                        </p>
                        <p className="mt-3 text-sm text-ink-mute">
                            {localDate || '—'}
                            {profile.location && <span className="text-ink-faint"> · {profile.location}</span>}
                        </p>

                        <div className="mt-7">
                            <div className="flex items-center justify-between">
                                <span className="eng-label">Day progress</span>
                                <span className="font-mono text-[11px] tabular-nums text-ink-mute">{Math.round(dayProgress * 100)}%</span>
                            </div>
                            <div className="mt-2 h-1.5 overflow-hidden border border-line bg-surface-2">
                                <motion.div
                                    initial={{ width: 0 }}
                                    animate={{ width: `${dayProgress * 100}%` }}
                                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                                    className="h-full bg-ink"
                                />
                            </div>
                            <p className="mt-3 eng-label">{profile.timeZone}</p>
                        </div>
                    </motion.div>
                </div>

                {/* Channels */}
                {profile.channels.length > 0 && (
                    <motion.div
                        initial={{ y: 18 }}
                        whileInView={{ y: 0 }}
                        viewport={{ once: true, margin: '-80px' }}
                        transition={{ duration: 0.5, delay: 0.16, ease: [0.16, 1, 0.3, 1] }}
                        className="mt-px grid gap-px border border-line border-t-0 bg-line sm:grid-cols-2 lg:grid-cols-3"
                    >
                        {profile.channels.map((channel, i) => (
                            <a
                                key={`${channel.label}-${channel.href}`}
                                href={channel.href}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="group flex items-center justify-between gap-4 bg-surface-1 px-6 py-5 text-sm"
                            >
                                <span className="flex min-w-0 items-center gap-3">
                                    <BrandIcon name={channel.label} className="size-5 shrink-0 text-ink-soft transition-colors duration-200 group-hover:text-accent" />
                                    <span className="min-w-0">
                                        <span className="block font-medium text-ink">
                                            <span className="mr-2 font-mono text-xs text-ink-faint">CH_{String(i + 2).padStart(2, '0')}</span>
                                            {channel.label}
                                        </span>
                                        {channel.handle && <span className="block truncate font-mono text-xs text-ink-mute">{channel.handle}</span>}
                                    </span>
                                </span>
                                <svg className="size-4 text-ink-faint transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                                    <path d="M7 17 17 7" />
                                    <path d="M7 7h10v10" />
                                </svg>
                            </a>
                        ))}
                    </motion.div>
                )}
            </div>
        </section>
    );
}
