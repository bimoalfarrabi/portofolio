import { motion } from 'motion/react';

export default function EscapeVelocity({ stats = [] }) {
    const activeStats = stats.map((stat) => ({ label: stat.label, value: stat.value, note: stat.note ?? '' }));

    return (
        <section className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink">
            <div className="pointer-events-none absolute inset-0 grid-hairline-soft opacity-50" />

            <div className="relative mx-auto max-w-6xl">
                <motion.div
                    data-reveal
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                    className="mb-12 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between"
                >
                    <div>
                        <p className="eng-label mb-3">SYS_TELEMETRY · 07</p>
                        <h2 className="max-w-3xl text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
                            Tiny stats,
                            <span className="block text-ink-faint">actual <span className="text-accent">signal</span>.</span>
                        </h2>
                    </div>
                    <p className="max-w-sm text-sm leading-7 text-ink-mute">
                        No fake skill percentages. Cuma beberapa indikator kecil tentang arah dan cara kerja.
                    </p>
                </motion.div>

                <div className="grid gap-px border border-line bg-line sm:grid-cols-2 lg:grid-cols-4">
                    {activeStats.map((stat, index) => (
                        <motion.div
                            key={stat.label}
                            initial={{ y: 18 }}
                            whileInView={{ y: 0 }}
                            viewport={{ once: true, margin: '-80px' }}
                            transition={{ duration: 0.5, delay: index * 0.07, ease: [0.16, 1, 0.3, 1] }}
                            className="group relative bg-surface-1 p-6"
                        >
                            <div className="mb-8 flex items-center justify-between">
                                <span className="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-mute">
                                    {String(index + 1).padStart(2, '0')} / {stat.label}
                                </span>
                                <span className="size-1.5 rounded-full bg-accent transition-transform duration-300 group-hover:scale-150" />
                            </div>
                            <p className="text-4xl font-semibold tracking-[-0.05em] text-ink">
                                {stat.value}
                            </p>
                            <p className="mt-3 text-sm text-ink-mute">
                                {stat.note}
                            </p>
                        </motion.div>
                    ))}
                </div>
            </div>
        </section>
    );
}
