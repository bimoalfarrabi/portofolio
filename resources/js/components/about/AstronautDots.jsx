import { useEffect, useMemo, useRef } from 'react';

const BASE_DOT_COUNT = 360;
const LINE_DISTANCE = 7.5;

function sampleArc(cx, cy, rx, ry, start, end, count) {
    return Array.from({ length: count }, (_, index) => {
        const t = count === 1 ? 0 : index / (count - 1);
        const angle = start + (end - start) * t;
        return { rx: cx + Math.cos(angle) * rx, ry: cy + Math.sin(angle) * ry };
    });
}

function traceProfile() {
    // Head — full circle, centered slightly above mid
    const head = sampleArc(50, 24, 11, 11.5, 0, Math.PI * 2, 38);

    // Helmet visor — inner ellipse, partial arc
    const visor = sampleArc(50, 24, 6.5, 6, Math.PI * 0.2, Math.PI * 0.88, 14);

    // Neck
    const neck = sampleArc(50, 37, 3.5, 2.5, 0, Math.PI, 10);

    // Suit collar / chest top — wide flat arc
    const collar = sampleArc(50, 42, 12, 4, Math.PI, Math.PI * 2, 18);

    // Left shoulder pad — rounded outer bump
    const leftShoulder = sampleArc(34, 50, 7, 9, Math.PI * 0.6, Math.PI * 1.55, 20);

    // Right shoulder pad
    const rightShoulder = sampleArc(66, 50, 7, 9, Math.PI * 1.45, Math.PI * 2.4, 20);

    // Left arm — outer edge going down
    const leftArm = sampleArc(30, 64, 5.5, 10, Math.PI * 0.85, Math.PI * 1.55, 16);

    // Right arm
    const rightArm = sampleArc(70, 64, 5.5, 10, Math.PI * 1.45, Math.PI * 2.15, 16);

    // Chest — front panel, wide ellipse top
    const chestTop = sampleArc(50, 52, 14, 5, Math.PI, Math.PI * 2, 20);

    // Chest panel detail — small rect-ish cluster center
    const chestPanel = sampleArc(50, 57, 5, 3.5, 0, Math.PI * 2, 16);

    // Torso sides — left and right vertical edges
    const torsoLeft = sampleArc(37, 68, 3, 14, Math.PI * 0.5, Math.PI * 1.5, 14);
    const torsoRight = sampleArc(63, 68, 3, 14, Math.PI * 1.5, Math.PI * 2.5, 14);

    // Suit bottom / waist — wide arc
    const waist = sampleArc(50, 80, 16, 5, 0, Math.PI, 24);

    return [
        ...head,
        ...visor,
        ...neck,
        ...collar,
        ...leftShoulder,
        ...rightShoulder,
        ...leftArm,
        ...rightArm,
        ...chestTop,
        ...chestPanel,
        ...torsoLeft,
        ...torsoRight,
        ...waist,
    ];
}

export default function AstronautDots({ active = false }) {
    const canvasRef = useRef(null);
    const activeRef = useRef(active);
    const outline = useMemo(traceProfile, []);

    useEffect(() => {
        activeRef.current = active;
    }, [active]);

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return undefined;

        const ctx = canvas.getContext('2d', { alpha: true });
        const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

        let width = 0;
        let height = 0;
        let dpr = 1;
        let animationFrame = 0;
        let lastTimestamp = 0;
        let elapsed = 0;
        let morphProgress = 0;
        let visible = true;
        let dots = [];
        let morphDots = [];

        function buildDots() {
            const area = Math.max(width * height, 1);
            const density = Math.min(1.4, Math.max(0.55, area / (1280 * 540)));
            const count = Math.round(BASE_DOT_COUNT * density);
            const morphCount = Math.min(outline.length, count);

            dots = Array.from({ length: count }, (_, index) => {
                const isMorphDot = index < morphCount;
                const tpt = outline[index % outline.length];
                return {
                    x: Math.random() * width,
                    y: Math.random() * height,
                    originX: Math.random() * width,
                    originY: Math.random() * height,
                    tx: tpt.rx,
                    ty: tpt.ry,
                    isMorphDot,
                    speed: 0.12 + Math.random() * 0.36,
                    phase: Math.random() * Math.PI * 2,
                    driftX: 14 + Math.random() * 32,
                    driftY: 10 + Math.random() * 26,
                    size: 1.0 + Math.random() * 0.7,
                    idleOpacity: 0.28 + Math.random() * 0.26,
                    breathe: 0.45 + Math.random() * 0.85,
                    breathePhase: Math.random() * Math.PI * 2,
                    morphIndex: isMorphDot ? index : -1,
                };
            });

            morphDots = dots.filter((dot) => dot.isMorphDot);
        }

        function resize() {
            const rect = canvas.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return;

            dpr = Math.min(window.devicePixelRatio || 1, 2);
            width = rect.width;
            height = rect.height;
            canvas.width = Math.floor(width * dpr);
            canvas.height = Math.floor(height * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            buildDots();
        }

        function drawStaticFrame() {
            ctx.clearRect(0, 0, width, height);
            for (const dot of dots) {
                ctx.beginPath();
                ctx.arc(dot.originX, dot.originY, dot.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(39, 39, 42, ${dot.idleOpacity})`;
                ctx.fill();
            }
        }

        function step(timestamp) {
            if (!visible) {
                animationFrame = 0;
                return;
            }

            if (!lastTimestamp) lastTimestamp = timestamp;
            const deltaMs = Math.min(timestamp - lastTimestamp, 48);
            lastTimestamp = timestamp;

            const dt = deltaMs / 1000;
            elapsed += dt;

            ctx.clearRect(0, 0, width, height);

            const targetMorph = activeRef.current ? 1 : 0;
            const morphLerp = 1 - Math.exp(-dt * 3.4);
            morphProgress += (targetMorph - morphProgress) * morphLerp;
            const ease = morphProgress * morphProgress * (3 - 2 * morphProgress);

            const shapeSize = Math.min(width, height) * 0.7;
            const centerX = width / 2;
            const centerY = height * 0.42;
            const waveB = Math.cos(elapsed * 0.95 + 1.2) * 1.6 * ease;
            const shapePulse = Math.sin(elapsed * 1.25) * 2.2 * ease;
            const positionLerp = 1 - Math.exp(-dt * 6);

            for (const dot of dots) {
                const idleX = dot.originX + Math.sin(elapsed * dot.speed + dot.phase) * dot.driftX;
                const idleY = dot.originY + Math.cos(elapsed * dot.speed * 0.82 + dot.phase * 1.15) * dot.driftY;
                const dotEase = dot.isMorphDot ? ease : 0;
                const aliveX = dot.isMorphDot ? Math.sin(elapsed * dot.breathe + dot.breathePhase) * 2.4 * ease : 0;
                const aliveY = dot.isMorphDot ? Math.cos(elapsed * dot.breathe * 0.8 + dot.breathePhase) * 1.9 * ease : 0;
                const localWave = Math.sin(elapsed * 1.2 + dot.phase) * 1.1 * ease + waveB * 0.22;
                const targetX = centerX + (shapeSize * (dot.tx - 50)) / 100 + aliveX + localWave;
                const targetY = centerY + (shapeSize * (dot.ty - 50)) / 100 + aliveY + shapePulse;

                const blendedX = idleX + (targetX - idleX) * dotEase;
                const blendedY = idleY + (targetY - idleY) * dotEase;
                dot.x += (blendedX - dot.x) * positionLerp;
                dot.y += (blendedY - dot.y) * positionLerp;

                const shimmer = dot.isMorphDot
                    ? (0.08 + Math.sin(elapsed * 2.2 + dot.breathePhase) * 0.06) * ease
                    : 0;
                const alpha = Math.min(
                    0.78,
                    dot.idleOpacity + (dot.isMorphDot ? (0.56 - dot.idleOpacity) * ease : 0) + shimmer * 0.35,
                );
                const size = dot.size + (dot.isMorphDot ? (0.32 + Math.sin(elapsed * 1.8 + dot.breathePhase) * 0.1) * ease : 0);

                ctx.beginPath();
                ctx.arc(dot.x, dot.y, size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(39, 39, 42, ${alpha})`;
                ctx.fill();
            }

            if (ease > 0.05 && morphDots.length > 1) {
                const lineAlphaBase = 0.22 * ease;
                const maxDistSq = LINE_DISTANCE * LINE_DISTANCE;
                ctx.lineWidth = 0.6;
                for (let i = 0; i < morphDots.length; i += 1) {
                    const a = morphDots[i];
                    for (let j = i + 1; j < Math.min(i + 5, morphDots.length); j += 1) {
                        const b = morphDots[j];
                        const dx = a.x - b.x;
                        const dy = a.y - b.y;
                        const distSq = dx * dx + dy * dy;
                        if (distSq < maxDistSq) {
                            const falloff = 1 - distSq / maxDistSq;
                            ctx.strokeStyle = `rgba(39, 39, 42, ${lineAlphaBase * falloff})`;
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.stroke();
                        }
                    }
                }
            }

            animationFrame = window.requestAnimationFrame(step);
        }

        function start() {
            if (animationFrame || !visible) return;
            lastTimestamp = 0;
            animationFrame = window.requestAnimationFrame(step);
        }

        function stop() {
            if (animationFrame) {
                window.cancelAnimationFrame(animationFrame);
                animationFrame = 0;
            }
        }

        resize();

        if (reduceMotion) {
            drawStaticFrame();
            return () => {};
        }

        const ro = new ResizeObserver(() => {
            resize();
        });
        ro.observe(canvas);

        const io = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    visible = entry.isIntersecting;
                    if (visible) start();
                    else stop();
                }
            },
            { threshold: 0.05 },
        );
        io.observe(canvas);

        const handleVisibility = () => {
            if (document.hidden) stop();
            else if (visible) start();
        };
        document.addEventListener('visibilitychange', handleVisibility);

        start();

        return () => {
            stop();
            ro.disconnect();
            io.disconnect();
            document.removeEventListener('visibilitychange', handleVisibility);
        };
    }, [outline]);

    return (
        <canvas
            ref={canvasRef}
            className="absolute inset-0 h-full w-full"
            aria-hidden="true"
        />
    );
}
