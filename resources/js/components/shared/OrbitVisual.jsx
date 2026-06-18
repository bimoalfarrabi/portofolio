import { motion, useReducedMotion } from 'motion/react';
import { useEffect, useRef, useState } from 'react';

const ringPercents = [44, 33, 22];

const orbitBodies = [
    { ring: 44, duration: 30, angle: 18, size: 9, accent: true, reverse: false },
    { ring: 44, duration: 34, angle: 142, size: 6, accent: false, reverse: true },
    { ring: 33, duration: 22, angle: 64, size: 7, accent: false, reverse: true },
    { ring: 33, duration: 27, angle: 234, size: 5, accent: false, reverse: false },
    { ring: 22, duration: 16, angle: 304, size: 5, accent: true, reverse: false },
];

export default function OrbitVisual() {
    const shouldReduceMotion = useReducedMotion();
    const containerRef = useRef(null);
    const [pxPerPercent, setPxPerPercent] = useState(420 / 100);

    useEffect(() => {
        const el = containerRef.current;
        if (!el) return;
        const ro = new ResizeObserver(([entry]) => {
            setPxPerPercent(entry.contentRect.width / 100);
        });
        ro.observe(el);
        return () => ro.disconnect();
    }, []);

    return (
        <motion.div
            ref={containerRef}
            initial={shouldReduceMotion ? { opacity: 0 } : { scale: 0.94 }}
            animate={shouldReduceMotion ? { opacity: 1 } : { scale: 1 }}
            transition={shouldReduceMotion ? { duration: 0.2 } : { duration: 1, delay: 0.35, ease: [0.16, 1, 0.3, 1] }}
            className="relative aspect-square w-[min(78vw,420px)]"
            aria-hidden="true"
        >
            {/* Base disc — solid surface, no halo */}
            <div className="absolute inset-0 rounded-full border border-line bg-surface-1" />
            <div className="absolute inset-[12%] rounded-full border border-line/70" />
            <div className="absolute inset-[30%] rounded-full border border-line/70 bg-surface-2" />

            {/* Crosshair guides */}
            <div className="absolute left-1/2 top-0 h-full w-px -translate-x-1/2 bg-line/50" />
            <div className="absolute top-1/2 left-0 h-px w-full -translate-y-1/2 bg-line/50" />

            {/* Corner readout */}
            <span className="absolute left-[6%] top-[6%] font-mono text-[10px] uppercase tracking-[0.2em] text-ink-faint">
                ORB_SYS
            </span>
            <span className="absolute bottom-[6%] right-[6%] font-mono text-[10px] uppercase tracking-[0.2em] text-ink-faint">
                R=44.33.22
            </span>

            {ringPercents.map((pct) => (
                <Ring key={pct} duration={34} pct={pct} shouldReduceMotion={shouldReduceMotion} />
            ))}

            {/* Center core */}
            <motion.div
                animate={shouldReduceMotion ? undefined : { scale: [1, 1.1, 1] }}
                transition={{ duration: 4, repeat: Infinity, ease: 'easeInOut' }}
                className="absolute left-1/2 top-1/2 size-4 -translate-x-1/2 -translate-y-1/2 rounded-full bg-accent"
            />

            {orbitBodies.map((body, i) => (
                <OrbitBody
                    key={i}
                    distance={body.ring * pxPerPercent}
                    duration={body.duration}
                    angle={body.angle}
                    size={body.size}
                    accent={body.accent}
                    reverse={body.reverse}
                    shouldReduceMotion={shouldReduceMotion}
                />
            ))}
        </motion.div>
    );
}

function Ring({ duration, pct, shouldReduceMotion = false }) {
    const r = `${pct}%`;
    return (
        <motion.svg
            viewBox="0 0 400 400"
            animate={shouldReduceMotion ? undefined : { rotate: 360 }}
            transition={{ duration, repeat: Infinity, ease: 'linear' }}
            className="absolute inset-0 size-full"
        >
            <circle
                cx="200"
                cy="200"
                r={r}
                fill="none"
                stroke="var(--color-line)"
                strokeWidth="1"
                strokeDasharray="2 7"
                vectorEffect="non-scaling-stroke"
            />
        </motion.svg>
    );
}

function OrbitBody({ distance, duration, angle, size, accent, reverse, shouldReduceMotion = false }) {
    const offset = distance - (size / 2);

    return (
        <motion.span
            animate={shouldReduceMotion ? { rotate: angle } : {
                rotate: reverse ? [angle, angle - 360] : [angle, angle + 360],
            }}
            transition={{ rotate: { duration, repeat: Infinity, ease: 'linear' } }}
            className="absolute left-1/2 top-1/2"
            style={{
                marginLeft: offset,
                marginTop: -(size / 2),
                width: size,
                height: size,
                transformOrigin: `${-offset}px ${size / 2}px`,
            }}
        >
            <span className={`block size-full rounded-full ${accent ? 'bg-accent' : 'bg-ink'}`} />
        </motion.span>
    );
}
