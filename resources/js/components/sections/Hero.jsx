import { motion } from 'motion/react';
import { OrbitVisual } from '../shared';

const copyVariants = {
    hidden: { y: 24, opacity: 0 },
    visible: (delay = 0) => ({
        y: 0,
        opacity: 1,
        transition: { duration: 0.8, delay, ease: [0.16, 1, 0.3, 1] },
    }),
};

export default function Hero() {
    return (
        <section
            id="intro"
            className="relative min-h-screen overflow-hidden bg-surface-0 pt-24 text-ink"
        >
            {/* Hairline grid — fixed, no banding */}
            <div className="pointer-events-none absolute inset-0 grid-hairline opacity-50" />

            {/* Top instrument bar */}
            <div className="pointer-events-none absolute inset-x-0 top-24 flex items-center justify-between px-5 sm:px-8">
                <span className="eng-label">SYS_HERO · 01</span>
                <span className="eng-label">SECTOR // ORBIT_RECON</span>
            </div>

            <div className="relative mx-auto grid min-h-[calc(100vh-6rem)] max-w-7xl items-center gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[1.05fr_0.95fr] lg:py-20">
                <div className="max-w-3xl">
                    <motion.div
                        variants={copyVariants}
                        initial="hidden"
                        animate="visible"
                        custom={0.05}
                        className="mb-7 inline-flex items-center gap-3 border border-line bg-surface-1 px-4 py-2"
                    >
                        <span className="relative flex size-2 items-center justify-center">
                            <span className="absolute inset-0 rounded-full bg-accent/35 starfield-blip" />
                            <span className="relative size-1.5 rounded-full bg-accent" />
                        </span>
                        <span className="eng-label !text-ink">SIGNAL_LOCKED</span>
                    </motion.div>

                    <motion.h1
                        variants={copyVariants}
                        initial="hidden"
                        animate="visible"
                        custom={0.18}
                        className="max-w-4xl text-[clamp(3.4rem,9.4vw,7.8rem)] font-semibold leading-[0.9] tracking-[-0.05em] text-ink"
                    >
                        Developer Laravel,
                        <span className="block text-ink-faint">pencerita <span className="text-accent">visual</span>.</span>
                    </motion.h1>

                    <motion.p
                        variants={copyVariants}
                        initial="hidden"
                        animate="visible"
                        custom={0.34}
                        className="mt-8 max-w-xl text-lg leading-8 text-ink-mute sm:text-xl"
                    >
                        Bukan portofolio template. Ruang eksperimen: progres, proses, dan project ditampilkan lewat pola visual yang lebih terasa seperti instrumen daripada halaman perkenalan.
                    </motion.p>

                    <motion.div
                        variants={copyVariants}
                        initial="hidden"
                        animate="visible"
                        custom={0.5}
                        className="mt-10 flex flex-col gap-3 sm:flex-row"
                    >
                        <a
                            href="#progress"
                            className="group inline-flex items-center justify-center gap-2 bg-ink px-7 py-3.5 text-sm font-medium uppercase tracking-[0.18em] text-surface-1 transition-colors duration-200 hover:bg-accent"
                        >
                            <span className="font-mono text-xs">[01]</span>
                            Mulai sinyal
                            <svg className="size-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                                <path d="M5 12h14" />
                                <path d="m13 6 6 6-6 6" />
                            </svg>
                        </a>
                        <a
                            href="#projects"
                            className="inline-flex items-center justify-center gap-2 border border-line bg-surface-1 px-7 py-3.5 text-sm font-medium uppercase tracking-[0.18em] text-ink transition-colors duration-200 hover:border-ink"
                        >
                            <span className="font-mono text-xs text-ink-mute">[02]</span>
                            Lihat orbit
                        </a>
                    </motion.div>

                    <motion.dl
                        variants={copyVariants}
                        initial="hidden"
                        animate="visible"
                        custom={0.62}
                        className="mt-14 grid max-w-md grid-cols-3 border border-line"
                    >
                        {[
                            { k: 'STATUS', v: 'Live', accent: true },
                            { k: 'STACK', v: 'Laravel · React' },
                            { k: 'LOC', v: 'WIB · GMT+7' },
                        ].map((item, i) => (
                            <div
                                key={item.k}
                                className={`px-4 py-3 ${i > 0 ? 'border-l border-line' : ''} bg-surface-1`}
                            >
                                <dt className="eng-label">{item.k}</dt>
                                <dd className={`mt-1 font-mono text-sm ${item.accent ? 'text-accent' : 'text-ink'}`}>
                                    {item.v}
                                </dd>
                            </div>
                        ))}
                    </motion.dl>
                </div>

                <motion.div
                    initial={{ x: 28 }}
                    animate={{ x: 0 }}
                    transition={{ duration: 0.9, delay: 0.42, ease: [0.16, 1, 0.3, 1] }}
                    className="relative mx-auto flex w-full justify-center lg:justify-end"
                >
                    <OrbitVisual />
                </motion.div>
            </div>
        </section>
    );
}
