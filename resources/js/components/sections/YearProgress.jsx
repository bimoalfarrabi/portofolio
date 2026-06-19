import { useEffect, useMemo, useState } from 'react';
import { motion } from 'motion/react';
import { OrbitVisual, StitchBackground } from '../shared';
import { useHoliday } from '../../hooks/useHoliday';
import { useTranslation } from '../../hooks/useLocale';

const timeZone = document.querySelector('meta[name="timezone"]')?.content || 'Asia/Jakarta';

function getYearProgress(now = new Date()) {
    const year = now.getFullYear();
    const start = new Date(year, 0, 1, 0, 0, 0, 0);
    const end = new Date(year + 1, 0, 1, 0, 0, 0, 0);
    const elapsed = now.getTime() - start.getTime();
    const total = end.getTime() - start.getTime();
    const progress = Math.min(Math.max(elapsed / total, 0), 1);

    return {
        year,
        progress,
        percentage: progress * 100,
        day: Math.floor(elapsed / 86_400_000) + 1,
        totalDays: Math.round(total / 86_400_000),
        daysRemaining: Math.max(Math.ceil((end.getTime() - now.getTime()) / 86_400_000), 0),
        hoursRemaining: Math.max(Math.ceil((end.getTime() - now.getTime()) / 3_600_000), 0),
    };
}

function formatWibDate(now) {
    return new Intl.DateTimeFormat('en-GB', {
        timeZone,
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(now);
}

function formatWibTime(now) {
    return new Intl.DateTimeFormat('id-ID', {
        timeZone,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    }).format(now);
}

function isSundayInWib() {
    const formatter = new Intl.DateTimeFormat('en-US', {
        timeZone,
        weekday: 'long',
    });
    return formatter.format(new Date()) === 'Sunday';
}

export default function YearProgress() {
    const { t } = useTranslation();
    const [now, setNow] = useState(() => new Date());
    const { holiday, loading } = useHoliday();

    useEffect(() => {
        const timer = window.setInterval(() => setNow(new Date()), 1000);
        return () => window.clearInterval(timer);
    }, []);

    const data = useMemo(() => getYearProgress(now), [now]);
    const isRedDay = (!loading && holiday) || isSundayInWib();

    return (
        <section
            id="progress"
            className="relative flex min-h-screen items-center justify-center overflow-hidden bg-surface-0 px-5 py-28 text-ink"
        >
            <div className="pointer-events-none absolute inset-0 grid-hairline opacity-40" />
            <StitchBackground />

            <motion.div
                initial={{ y: 24 }}
                whileInView={{ y: 0 }}
                viewport={{ once: true, margin: '-80px' }}
                transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                className="frame relative z-10 w-full max-w-5xl p-8 sm:p-14 lg:p-16"
            >
                <span className="frame-corner bl" />
                <span className="frame-corner br" />

                <div className="mb-10 flex items-center justify-between">
                    <span className="eng-label">{t('progress.label')}</span>
                    <span className="eng-label">CYCLE // {data.year}</span>
                </div>

                <div className="flex flex-col gap-12 lg:flex-row lg:items-end lg:justify-between">
                    <div className="space-y-3">
                        <p className="eng-label">{t('progress.bar.label')}</p>
                        <h1 className="text-[clamp(4rem,14vw,9rem)] font-semibold leading-[0.82] tracking-[-0.05em] text-ink">
                            {data.percentage.toFixed(2)}<span className="text-accent">%</span>
                        </h1>
                    </div>

                    <div className="relative border border-line bg-surface-2 px-7 py-6 text-right lg:min-w-80">
                        <span className="absolute left-3 top-3 size-1.5 rounded-full bg-accent starfield-blip" />
                        <p className={`text-base font-medium ${isRedDay ? 'text-accent' : 'text-ink'}`}>
                            {formatWibDate(now)}
                        </p>
                        <p className="mt-2 font-mono text-4xl tracking-[-0.03em] text-ink">
                            {formatWibTime(now)}
                        </p>
                        <p className="mt-3 eng-label">WIB · GMT+7</p>
                        {!loading && holiday && (
                            <p className="mt-1 text-xs font-medium text-accent">
                                {holiday.description_en ?? holiday.description}
                            </p>
                        )}
                    </div>
                </div>

                <div className="mt-16">
                    <div className="mb-4 flex items-center justify-between font-mono text-xs uppercase tracking-[0.16em] text-ink-mute">
                        <span>{t('progress.day')} {data.day} / {data.totalDays}</span>
                        <span>
                            {data.daysRemaining} {t('progress.days.left')}
                            <span className="ml-2 text-ink-faint">· {data.hoursRemaining.toLocaleString()} {t('progress.hrs')}</span>
                        </span>
                    </div>

                    <div className="relative h-7 overflow-hidden border border-line bg-surface-2">
                        <div className="pointer-events-none absolute inset-0 tick-bar opacity-40" />
                        <motion.div
                            initial={{ width: 0 }}
                            whileInView={{ width: `${data.progress * 100}%` }}
                            viewport={{ once: true }}
                            transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }}
                            className="absolute inset-y-0 left-0 bg-ink"
                        >
                            <span className="absolute right-0 top-0 h-full w-0.5 bg-accent" />
                        </motion.div>
                    </div>

                    <div className="mt-4 flex justify-between font-mono text-xs uppercase tracking-[0.16em] text-ink-faint">
                        <span>Jan 01</span>
                        <span>Dec 31</span>
                    </div>
                </div>
            </motion.div>
        </section>
    );
}
