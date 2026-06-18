import { useMemo } from 'react';
import { motion } from 'motion/react';
import { BrandIcon } from '../shared';

// Seeded pseudo-random untuk jitter yang konsisten (tidak berubah setiap render)
function seededRandom(seed) {
    const x = Math.sin(seed + 1) * 10000;
    return x - Math.floor(x);
}

function buildOrbitNodes(nodes) {
    const total = nodes.length;
    if (!total) return [];

    const PADDING_X = 12; // % dari kiri/kanan
    const PADDING_Y = 10; // % dari atas/bawah
    const AREA_W = 100 - PADDING_X * 2;
    const AREA_H = 100 - PADDING_Y * 2;

    // Hitung grid cols/rows: prioritas lebar cell agar label tidak overlap
    // aspect container 4:3, jadi beri bobot lebih ke cols
    const cols = Math.ceil(Math.sqrt(total * (4 / 3)));
    const rows = Math.ceil(total / cols);

    const cellW = AREA_W / cols;
    const cellH = AREA_H / rows;

    // Jitter kecil hanya di sumbu Y agar baris terasa organik tanpa risiko overlap horizontal
    const jitterY = cellH * 0.18;

    return nodes.map((node, index) => {
        const col = index % cols;
        const row = Math.floor(index / cols);

        // Offset baris ganjil ke kanan setengah cell (brickwork pattern)
        const brickOffset = (row % 2 === 1) ? cellW * 0.5 : 0;

        const cx = PADDING_X + col * cellW + cellW / 2 + brickOffset;
        const cy = PADDING_Y + row * cellH + cellH / 2;

        // Jitter Y saja — deterministik per node
        const jy = (seededRandom(index) - 0.5) * 2 * jitterY;

        return {
            ...node,
            x: `${Math.max(PADDING_X, Math.min(100 - PADDING_X, cx)).toFixed(2)}%`,
            y: `${Math.max(PADDING_Y, Math.min(100 - PADDING_Y, cy + jy)).toFixed(2)}%`,
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
                        className="flex origin-center items-center gap-1 whitespace-nowrap border border-line bg-surface-1 px-2 py-1 text-[10px] font-medium text-ink-soft transition-colors duration-150 ease-out group-hover:border-ink group-hover:text-ink sm:gap-2 sm:px-3.5 sm:py-1.5 sm:text-sm"
                    >
                        <BrandIcon name={node.icon || node.label} className={`size-3 sm:size-3.5 ${idx % 4 === 0 ? 'text-accent' : 'text-ink'}`} />
                        {node.label}
                    </motion.span>
                </motion.button>
            ))}
        </motion.div>
    );
}
