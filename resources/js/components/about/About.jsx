import { useEffect, useRef, useState } from 'react';
import { motion } from 'motion/react';
import AstronautDots from './AstronautDots';

export default function About() {
    const [profileActive, setProfileActive] = useState(false);
    const [isTouchDevice, setIsTouchDevice] = useState(false);
    const sectionRef = useRef(null);

    useEffect(() => {
        const noHover = !(window.matchMedia?.('(hover: hover)').matches ?? true);
        setIsTouchDevice(noHover);

        if (!noHover) return undefined;

        // Touch device: auto-activate when section is sufficiently visible,
        // deactivate when scrolled away.
        const node = sectionRef.current;
        if (!node) return undefined;

        const observer = new IntersectionObserver(
            ([entry]) => setProfileActive(entry.intersectionRatio > 0.25),
            { threshold: [0, 0.1, 0.25, 0.5, 0.75] },
        );
        observer.observe(node);
        return () => observer.disconnect();
    }, []);

    const handleTap = () => {
        if (!isTouchDevice) return;
        setProfileActive((prev) => !prev);
    };

    return (
        <section
            ref={sectionRef}
            id="about"
            onMouseEnter={() => !isTouchDevice && setProfileActive(true)}
            onMouseLeave={() => !isTouchDevice && setProfileActive(false)}
            onFocus={() => setProfileActive(true)}
            onBlur={(event) => {
                if (!event.currentTarget.contains(event.relatedTarget)) {
                    setProfileActive(false);
                }
            }}
            onClick={handleTap}
            className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink"
        >
            <div className="pointer-events-none absolute inset-0 z-[1] opacity-35">
                <AstronautDots active={profileActive} />
            </div>

            <div className="relative z-10 mx-auto max-w-6xl">
                <motion.div
                    data-reveal
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                    className="mx-auto max-w-3xl text-center"
                >
                    <p className="eng-label mb-3">Origin signal · 03</p>
                    <h2 className="text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
                        Hai, saya
                        <span className="block text-accent">Bimo Alfarrabi.</span>
                    </h2>
                    <p className="mt-6 text-base leading-8 text-ink-mute">
                        Saya seorang pengembang &mdash; pakai Laravel di back-end, React/Vue di front-end.
                        Suka bikin situs web yang enak dilihat, terasa bernapas, dan selesai tepat waktu.
                    </p>
                    <p className="mt-4 text-base leading-8 text-ink-mute">
                        Yang saya cari bukan &ldquo;skill set paling lengkap&rdquo;, tapi cara kerja yang masuk akal,
                        desain yang tidak berisik, dan project yang benar-benar selesai.
                    </p>
                    <p className="mt-8 eng-label">
                        {isTouchDevice
                            ? (profileActive ? 'Ketuk lagi untuk sembunyikan siluet' : 'Ketuk bagian ini untuk menampilkan siluet profil')
                            : 'Arahkan kursor ke sini untuk menampilkan siluet profil'
                        }
                    </p>
                </motion.div>

                <motion.div
                    initial={{ y: 12 }}
                    whileInView={{ y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.7, delay: 0.5, ease: [0.16, 1, 0.3, 1] }}
                    className="relative mx-auto mt-10 max-w-3xl"
                >
                    {/* Divider */}
                    <div className="mb-6 flex items-center gap-4">
                        <div className="h-px flex-1 bg-line" />
                        <span className="font-mono text-[10px] uppercase tracking-[0.28em] text-ink-faint">CONTACT_CHANNELS</span>
                        <div className="h-px flex-1 bg-line" />
                    </div>

                    {/* Contact cards — grid tile style */}
                    <div className="grid grid-cols-1 gap-px border border-line bg-line sm:grid-cols-3">
                        {[
                            {
                                label: 'Email',
                                href: 'mailto:bimoalfarrabi24@gmail.com',
                                handle: 'bimoalfarrabi24',
                                channel: 'email',
                                delay: 0.55,
                                icon: (
                                    <svg className="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                ),
                            },
                            {
                                label: 'LinkedIn',
                                href: 'https://linkedin.com/in/bimoalfarrabi',
                                handle: 'bimoalfarrabi',
                                channel: 'linkedin',
                                delay: 0.62,
                                icon: (
                                    <svg className="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                ),
                            },
                            {
                                label: 'GitHub',
                                href: 'https://github.com/bimoalfarrabi',
                                handle: 'bimoalfarrabi',
                                channel: 'github',
                                delay: 0.69,
                                icon: (
                                    <svg className="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12" />
                                    </svg>
                                ),
                            },
                        ].map((link) => (
                            <motion.a
                                key={link.label}
                                href={link.href}
                                target={link.href.startsWith('http') ? '_blank' : undefined}
                                rel={link.href.startsWith('http') ? 'noopener noreferrer' : undefined}
                                initial={{ y: 10 }}
                                whileInView={{ y: 0 }}
                                viewport={{ once: true }}
                                transition={{ duration: 0.45, delay: link.delay, ease: [0.16, 1, 0.3, 1] }}
                                whileHover={{ y: -2 }}
                                className="group flex items-center gap-4 bg-surface-1 px-5 py-4 transition-colors hover:bg-surface-2"
                                aria-label={link.label}
                            >
                                <span className="text-ink-faint transition-colors duration-200 group-hover:text-accent">
                                    {link.icon}
                                </span>
                                <span className="min-w-0 flex-1">
                                    <span className="block font-mono text-[10px] uppercase tracking-[0.24em] text-ink-faint">{link.channel}</span>
                                    <span className="mt-0.5 block truncate text-sm font-medium text-ink-soft group-hover:text-ink">{link.handle}</span>
                                </span>
                                <svg className="size-3.5 shrink-0 translate-x-0 text-ink-faint opacity-0 transition-all duration-200 group-hover:translate-x-0.5 group-hover:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </motion.a>
                        ))}
                    </div>

                    {/* Status readout bar */}
                    <div className="mt-px flex items-center justify-between border border-t-0 border-line bg-surface-2 px-5 py-2.5">
                        <span className="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-faint">IDN &middot; UTC+7</span>
                        <span className="flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.24em] text-ink-faint">
                            <motion.span
                                animate={{ opacity: [1, 0.2, 1] }}
                                transition={{ duration: 2.4, repeat: Infinity, ease: 'easeInOut' }}
                                className="size-1.5 rounded-full bg-success"
                            />
                            Tersedia
                        </span>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
