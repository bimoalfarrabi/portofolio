import { useMemo } from 'react';
import { motion, useMotionValue, useTransform } from 'motion/react';

/**
 * Distributes projects using golden-angle spiral + force-relaxation.
 * Accounts for node size AND label height to prevent any visual overlap.
 *
 * 1. Initial placement via golden-angle spiral.
 * 2. Force-relaxation with generous MIN_DIST that includes label space.
 * 3. Smart label placement (top/bottom) based on neighbor positions.
 */
function buildOrbitProjects(projects) {
    const total = projects.length;
    if (!total) return [];

    const GOLDEN_ANGLE = Math.PI * (3 - Math.sqrt(5));
    const CENTER_X = 50;
    const CENTER_Y = 50;
    const MIN_RADIUS = 16;
    const MAX_RADIUS = 42;
    const ASPECT_RATIO = 0.75; // 4:3 container

    // Minimum distance accounts for node circle (~8-12% wide) + label (~6% tall).
    // This ensures both the circle AND label don't overlap with neighbors.
    const NODE_VISUAL_SIZE = total <= 4 ? 14 : total <= 7 ? 12 : 10;
    const LABEL_HEIGHT = 5;
    const MIN_DIST = NODE_VISUAL_SIZE + LABEL_HEIGHT;

    // Boundary padding — generous to keep labels from clipping
    const PAD_X = 12;
    const PAD_Y = 14; // extra vertical for labels
    const X_MIN = PAD_X;
    const X_MAX = 100 - PAD_X;
    const Y_MIN = PAD_Y;
    const Y_MAX = 100 - PAD_Y;

    // --- Step 1: Golden-angle spiral initial placement ---
    const points = projects.map((_, index) => {
        const angle = index * GOLDEN_ANGLE;
        const r = total === 1
            ? MIN_RADIUS
            : MIN_RADIUS + (MAX_RADIUS - MIN_RADIUS) * Math.sqrt(index / (total - 1));

        return {
            x: CENTER_X + Math.cos(angle) * r,
            y: CENTER_Y + Math.sin(angle) * r * ASPECT_RATIO,
        };
    });

    // --- Step 2: Force-relaxation to resolve overlaps ---
    const ITERATIONS = 120;
    const REPULSION_STRENGTH = 0.6;

    for (let iter = 0; iter < ITERATIONS; iter++) {
        let settled = true;

        for (let i = 0; i < total; i++) {
            for (let j = i + 1; j < total; j++) {
                const dx = points[j].x - points[i].x;
                const dy = points[j].y - points[i].y;
                const dist = Math.hypot(dx, dy);

                if (dist < MIN_DIST && dist > 0.01) {
                    settled = false;
                    const overlap = (MIN_DIST - dist) / 2;
                    const pushX = (dx / dist) * overlap * REPULSION_STRENGTH;
                    const pushY = (dy / dist) * overlap * REPULSION_STRENGTH;

                    points[i].x -= pushX;
                    points[i].y -= pushY;
                    points[j].x += pushX;
                    points[j].y += pushY;
                }
            }


        }

        // Clamp to boundaries after each iteration
        for (let i = 0; i < total; i++) {
            points[i].x = Math.max(X_MIN, Math.min(X_MAX, points[i].x));
            points[i].y = Math.max(Y_MIN, Math.min(Y_MAX, points[i].y));
        }

        if (settled) break;
    }

    // --- Step 3: Smart label placement ---
    // Place label on the side (top/bottom) with more free space from neighbors.
    const labelPlacements = points.map((point, i) => {
        let neighborsAbove = 0;
        let neighborsBelow = 0;

        for (let j = 0; j < total; j++) {
            if (j === i) continue;
            const dy = points[j].y - point.y;
            const dx = Math.abs(points[j].x - point.x);
            // Only count neighbors that are horizontally close enough to cause label collision
            if (dx < NODE_VISUAL_SIZE) {
                if (dy < 0) neighborsAbove++;
                else neighborsBelow++;
            }
        }

        // Also prefer bottom if node is near top edge, top if near bottom edge
        const topSpace = point.y - Y_MIN;
        const bottomSpace = Y_MAX - point.y;

        if (topSpace < LABEL_HEIGHT + 2) return 'bottom';
        if (bottomSpace < LABEL_HEIGHT + 2) return 'top';

        return neighborsAbove <= neighborsBelow ? 'top' : 'bottom';
    });

    // --- Step 4: Map back to project data ---
    return projects.map((project, index) => ({
        ...project,
        x: `${points[index].x.toFixed(2)}%`,
        y: `${points[index].y.toFixed(2)}%`,
        labelPlacement: labelPlacements[index],
    }));
}

/**
 * Builds constellation-style links between nearby nodes rather than sequential.
 * Connects each node to its 2 nearest neighbors for a natural web look.
 */
function buildOrbitLinks(projects) {
    if (projects.length < 2) return [];

    const coords = projects.map((p) => ({
        x: parseFloat(p.x),
        y: parseFloat(p.y),
    }));

    const links = [];
    const seen = new Set();
    const maxConnections = Math.min(2, projects.length - 1);

    coords.forEach((node, i) => {
        const distances = coords
            .map((other, j) => ({
                j,
                dist: Math.hypot(node.x - other.x, node.y - other.y),
            }))
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

/**
 * Returns dynamic size classes based on total number of projects.
 * Featured project gets a larger node regardless of its position in the array.
 */
function getDynamicSize(total, isFeatured) {
    if (total <= 3) {
        return isFeatured ? 'size-28 sm:size-32 lg:size-36' : 'size-20 sm:size-24 lg:size-26';
    }
    if (total <= 6) {
        return isFeatured ? 'size-22 sm:size-26 lg:size-28' : 'size-16 sm:size-18 lg:size-20';
    }
    if (total <= 9) {
        return isFeatured ? 'size-18 sm:size-22 lg:size-24' : 'size-14 sm:size-16 lg:size-18';
    }
    // 10+ items
    return isFeatured ? 'size-16 sm:size-18 lg:size-20' : 'size-12 sm:size-14 lg:size-16';
}

export default function ProjectOrbit({ projects, tone, direction, activeLabel, onSelectProject }) {
    const isDark = tone === 'dark';
    const xFrom = direction === 'open' ? -24 : 24;
    const xTo = direction === 'open' ? 0 : 0;
    const parallaxX = useMotionValue(0);
    const parallaxY = useMotionValue(0);
    const rotateX = useTransform(parallaxY, [-1, 1], [1, -1]);
    const rotateY = useTransform(parallaxX, [-1, 1], [-1.4, 1.4]);

    const handlePointerMove = (event) => {
        const bounds = event.currentTarget.getBoundingClientRect();
        const x = (event.clientX - bounds.left) / bounds.width;
        const y = (event.clientY - bounds.top) / bounds.height;
        parallaxX.set((x - 0.5) * 2);
        parallaxY.set((y - 0.5) * 2);
    };

    const resetParallax = () => {
        parallaxX.set(0);
        parallaxY.set(0);
    };

    const orbitProjects = useMemo(() => buildOrbitProjects(projects), [projects]);
    const orbitLinks = useMemo(() => buildOrbitLinks(orbitProjects), [orbitProjects]);
    const totalProjects = projects.length;

    return (
        <motion.div
            key={tone}
            initial={{ scale: 0.98, x: xFrom, y: 10 }}
            animate={{ scale: 1, x: xTo, y: 0 }}
            exit={{ opacity: 0, scale: 0.98, x: direction === 'open' ? 24 : -24, y: 10 }}
            transition={{ duration: 0.55, ease: [0.16, 1, 0.3, 1] }}
            onPointerMove={handlePointerMove}
            onPointerLeave={resetParallax}
            style={{ perspective: 1000, rotateX, rotateY }}
            className={`frame relative aspect-[4/3] ${
                isDark ? '!bg-[#15140F] !border-white/15 text-surface-1' : 'text-ink'
            }`}
        >
            <span className="frame-corner bl" />
            <span className="frame-corner br" />

            <span className={`absolute left-4 top-3 font-mono text-[10px] uppercase tracking-[0.2em] ${isDark ? 'text-white/40' : 'text-ink-faint'}`}>
                {isDark ? 'CLASSIFIED // ORBIT' : 'PUBLIC // ORBIT'}
            </span>
            <span className={`absolute right-4 bottom-3 font-mono text-[10px] uppercase tracking-[0.2em] ${isDark ? 'text-white/40' : 'text-ink-faint'}`}>
                N={totalProjects}
            </span>

            <div
                className={`pointer-events-none absolute inset-0 ${
                    isDark
                        ? 'bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:28px_28px]'
                        : 'grid-hairline-soft opacity-60'
                }`}
            />

            {/* Radar sweep */}
            <motion.div
                key={`${tone}-${direction}`}
                initial={{ rotate: 0, opacity: 0 }}
                animate={{ rotate: 360, opacity: [0, isDark ? 0.3 : 0.18, 0] }}
                transition={{ duration: 1.2, ease: 'linear', repeat: Infinity, repeatType: 'loop' }}
                className="absolute left-1/2 top-1/2 z-10 h-[115%] w-[115%] -translate-x-1/2 -translate-y-1/2 rounded-full"
                style={{
                    clipPath: 'polygon(50% 0%, 50.8% 0%, 50.8% 100%, 50% 100%)',
                    borderLeft: `1px solid ${isDark ? 'rgba(255,255,255,0.18)' : 'var(--color-accent)'}`,
                }}
            />

            <svg className="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                {orbitLinks.map((link) => (
                    <motion.line
                        key={link.key}
                        x1={link.x1}
                        y1={link.y1}
                        x2={link.x2}
                        y2={link.y2}
                        initial={{ pathLength: 0, opacity: 0 }}
                        animate={{ pathLength: 1, opacity: 1 }}
                        transition={{ duration: 0.9, delay: 0.15, ease: [0.16, 1, 0.3, 1] }}
                        stroke={isDark ? 'rgba(255,255,255,0.14)' : 'var(--color-line)'}
                        strokeWidth="0.45"
                        strokeLinecap="round"
                    />
                ))}
            </svg>

            {orbitProjects.map((project, index) => (
                <motion.button
                    key={project.name}
                    type="button"
                    initial={{ scale: 0.85, y: 12 }}
                    animate={{ scale: 1, y: 0 }}
                    transition={{ duration: 0.6, delay: project.delay, ease: [0.16, 1, 0.3, 1] }}
                    whileHover={{ scale: 1.04 }}
                    whileTap={{ scale: 0.98 }}
                    onClick={() => onSelectProject(project)}
                    className={`group absolute -translate-x-1/2 -translate-y-1/2 cursor-pointer ${getDynamicSize(totalProjects, project.isFeatured)}`}
                    style={{ left: project.x, top: project.y }}
                    aria-label={project.name}
                >
                    <span
                        className={`flex size-full items-center justify-center rounded-full border transition-colors duration-300 ${
                            isDark
                                ? 'border-white/15 bg-white/[0.04] group-hover:border-white/40'
                                : 'border-line bg-surface-1 group-hover:border-ink'
                        }`}
                    >
                        <span className={`size-2 rounded-full ${project.isFeatured ? 'bg-accent' : (isDark ? 'bg-white/80' : 'bg-ink')}`} />
                    </span>
                    <span
                        className={`pointer-events-none absolute left-1/2 w-max -translate-x-1/2 border px-2.5 py-1 font-mono text-[11px] font-medium uppercase tracking-[0.08em] ${
                            project.labelPlacement === 'top' ? 'bottom-full mb-2.5' : 'top-full mt-2.5'
                        } ${
                            isDark
                                ? 'border-white/15 bg-[#15140F] text-zinc-100'
                                : 'border-line bg-surface-1 text-ink-soft'
                        }`}
                    >
                        {project.name}
                    </span>
                </motion.button>
            ))}
        </motion.div>
    );
}
