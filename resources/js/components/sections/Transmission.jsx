import { motion } from 'motion/react';

const containerVariants = {
    hidden: {},
    visible: { transition: { staggerChildren: 0.12, delayChildren: 0.15 } },
};

const itemVariants = {
    hidden: { opacity: 0, x: -16 },
    visible: { opacity: 1, x: 0, transition: { duration: 0.55, ease: [0.16, 1, 0.3, 1] } },
};

export default function Transmission({ logs = [] }) {
    const transmissions = logs.map((log, index) => ({
        id: logs.length - index,
        text: log.body || log.title,
    }));

    return (
        <section id="signal" className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink">
            <div className="pointer-events-none absolute inset-0 grid-hairline-soft opacity-50" />

            <motion.div
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, margin: '-80px' }}
                variants={containerVariants}
                className="relative mx-auto max-w-3xl"
            >
                <motion.div variants={itemVariants} className="mb-4 flex items-center justify-between">
                    <p className="eng-label">SYS_LOG · 04</p>
                    <span className="eng-label">CH // OPEN</span>
                </motion.div>

                <motion.h2
                    variants={itemVariants}
                    className="mb-12 text-[clamp(2rem,5vw,3.4rem)] font-semibold leading-[1.05] tracking-[-0.04em] text-ink"
                >
                    Seperti apa portofolio ini
                    <br />
                    <span className="text-ink-faint">saat ia <span className="text-accent">bergerak</span>.</span>
                </motion.h2>

                <div className="border border-line bg-surface-1">
                    {transmissions.map((t, i) => (
                        <motion.div
                            key={t.id}
                            variants={itemVariants}
                            className={`flex items-start gap-4 px-5 py-4 ${i > 0 ? 'border-t border-line' : ''}`}
                        >
                            <span className="mt-0.5 font-mono text-xs text-ink-faint tabular-nums">
                                LOG_{String(t.id).padStart(3, '0')}
                            </span>
                            <p className="flex-1 text-sm leading-relaxed text-ink-soft">
                                {t.text}
                            </p>
                            <span className="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent" />
                        </motion.div>
                    ))}
                </div>

                <motion.p variants={itemVariants} className="mt-6 eng-label">
                    Sinyal lainnya akan segera hadir.
                </motion.p>
            </motion.div>
        </section>
    );
}
