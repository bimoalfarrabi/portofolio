import { useMemo } from 'react';
import { motion } from 'motion/react';
import { BrandIcon } from '../shared';

function buildOrbitNodes(nodes) {
    const total = nodes.length;
    if (!total) return [];

    const GOLDEN_ANGLE = Math.PI * (3 - Math.sqrt(5));
    const CENTER_X = 50;
    const CENTER_Y = 50;
    const MIN_RADIUS = 10;
    const MAX_RADIUS = 34;
    const ASPECT_RATIO = 0.75;

    return nodes.map((node, index) => {
        const angle = index * GOLDEN_ANGLE;
        const normalizedRadius = total === 1
            ? MIN_RADIUS
            : MIN_RADIUS + (MAX_RADIUS - MIN_RADIUS) * Math.sqrt(index / (total - 1));

        const x = CENTER_X + Math.cos(angle) * normalizedRadius;
        const y = CENTER_Y + Math.sin(angle) * normalizedRadius * ASPECT_RATIO;

        return {
            ...node,
            x: `${Math.max(10, Math.min(90, x))}%`,
            y: `${Math.max(10, Math.min(90, y))}%`,
        };
    });
}

function buildOrbitLinks(nodes) {
    if (nodes.length < 2) return [];

    const coords = nodes.map((n) => ({ x: parseFloat(n.x), y: parseFloat(n.y) }));
    const links = [];
    const seen = new Set();
    const maxConnections = Math.min(2, nodes.length - 1);

    coords.forEach((node, i) => {
        const distances = coords
            .map((other, j) => ({ j, dist: Math.hypot(node.x - other.x, node.y - other.y) }))
            .filter(({ j }) => j !== i)
            .sort((a, b) => a.dist - b.dist)
            .slice(0, maxConnections);

        distances.forEach(({ j }) => {
            const key = `${Math.min(i, j)}-${Math.max(i, j)}`;
            if (!seen.has(key)) {
                seen.add(key);
                links.push({
                    key,
                    x1: `${coords[i].x}`,
                    y1: `${coords[i].y}`,
                    x2: `${coords[j].x}`,
                    y2: `${coords[j].y}`,
                });
            }
        });
    });

    return links;
}

export default function ConstellationOrbit({ nodes }) {
    const orbitNodes = useMemo(() => buildOrbitNodes(nodes), [nodes]);
    const orbitLinks = useMemo(() => buildOrbitLinks(orbitNodes), [orbitNodes]);

    return (
        <motion.div
            initial={{ scale: 0.97 }}
            whileInView={{ scale: 1 }}
            viewport={{ once: true, margin: '-80px' }}
            transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }}
            className="frame relative aspect-[4/3]"
        >
            <span className="frame-corner bl" />
            <span className="frame-corner br" />

            <span className="absolute left-4 top-3 eng-label">NODE_MAP // {orbitNodes.length}</span>
            <span className="absolute right-4 bottom-3 eng-label">PROJ // 2D</span>

            <div className="pointer-events-none absolute inset-0 grid-hairline-soft opacity-60" />

            <svg className="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                {orbitLinks.map((link, index) => (
                    <motion.line
                        key={link.key}
                        x1={link.x1}
                        y1={link.y1}
                        x2={link.x2}
                        y2={link.y2}
                        initial={{ pathLength: 0, opacity: 0 }}
                        whileInView={{ pathLength: 1, opacity: 1 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.9, delay: 0.12 + index * 0.04, ease: [0.16, 1, 0.3, 1] }}
                        stroke="var(--color-line)"
                        strokeWidth="0.45"
                        strokeLinecap="round"
                    />
                ))}
            </svg>

            {orbitNodes.map((node, idx) => (
                <motion.button
                    key={node.label}
                    type="button"
                    initial={{ scale: 0.85 }}
                    whileInView={{ scale: 1 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.5, delay: node.delay, ease: [0.16, 1, 0.3, 1] }}
                    className="group absolute -translate-x-1/2 -translate-y-1/2"
                    style={{ left: node.x, top: node.y }}
                    aria-label={node.label}
                >
                    <motion.span
                        initial={false}
                        whileHover={{ y: -1 }}
                        whileTap={{ y: 0 }}
                        transition={{ type: 'spring', stiffness: 340, damping: 22, mass: 0.5 }}
                        className="flex origin-center items-center gap-2 whitespace-nowrap border border-line bg-surface-1 px-3.5 py-1.5 text-sm font-medium text-ink-soft transition-colors duration-150 ease-out group-hover:border-ink group-hover:text-ink"
                    >
                        <BrandIcon name={node.icon || node.label} className={`size-3.5 ${idx % 4 === 0 ? 'text-accent' : 'text-ink'}`} />
                        {node.label}
                    </motion.span>
                </motion.button>
            ))}
        </motion.div>
    );
}
