import { motion, useReducedMotion } from 'motion/react';
import { BrandIcon } from '../shared';

export default function ConstellationOrbit({ nodes }) {
    const reduced = useReducedMotion();

    return (
        <motion.div
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-80px' }}
            transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
            className="frame relative p-5 sm:p-6"
        >
            {/* Frame labels */}
            <span className="absolute left-4 top-3 eng-label">SYS_NODES // {nodes.length}</span>
            <span className="absolute right-4 bottom-3 eng-label">SIGNAL // ACTIVE</span>

            {/* Grid hairline background */}
            <div className="pointer-events-none absolute inset-0 grid-hairline-soft opacity-40" />

            {/* Signal Grid — 4 kolom, auto rows */}
            <div className="relative mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                {nodes.map((node, idx) => (
                    <motion.div
                        key={node.label}
                        initial={reduced ? false : { opacity: 0, scale: 0.88 }}
                        whileInView={{ opacity: 1, scale: 1 }}
                        viewport={{ once: true, amount: 0.3 }}
                        transition={{
                            duration: 0.45,
                            delay: idx * 0.055,
                            ease: [0.16, 1, 0.3, 1],
                        }}
                        className="group relative"
                    >
                        {/* Cell */}
                        <motion.button
                            type="button"
                            whileHover={reduced ? {} : { y: -2 }}
                            whileTap={reduced ? {} : { y: 0, scale: 0.97 }}
                            transition={{ type: 'spring', stiffness: 360, damping: 24, mass: 0.5 }}
                            className="relative flex w-full items-center gap-2 border border-line bg-surface-1 px-3 py-2.5 text-left transition-colors duration-150 ease-out group-hover:border-accent/60 group-hover:bg-surface-2"
                            aria-label={node.label}
                        >
                            {/* Index */}
                            <span className="eng-label shrink-0 text-[9px] text-ink-faint sm:text-[10px]">
                                {String(idx + 1).padStart(2, '0')}
                            </span>

                            {/* Icon */}
                            <BrandIcon
                                name={node.icon || node.label}
                                className="size-3.5 shrink-0 text-ink transition-colors duration-150 group-hover:text-accent sm:size-4"
                            />

                            {/* Label */}
                            <span className="truncate text-[11px] font-medium text-ink-soft transition-colors duration-150 group-hover:text-ink sm:text-xs">
                                {node.label}
                            </span>
                        </motion.button>
                    </motion.div>
                ))}
            </div>

            {/* Frame corners */}
            <span className="frame-corner bl" />
            <span className="frame-corner br" />
        </motion.div>
    );
}
