import { useEffect, useRef } from 'react';

export default function StitchBackground() {
    const canvasRef = useRef(null);

    useEffect(() => {
        const canvas = canvasRef.current;
        const context = canvas.getContext('2d');
        const dots = [];
        const pointer = { x: -9999, y: -9999, clientX: -9999, clientY: -9999, active: false };
        let width = 0;
        let height = 0;
        let time = 0;
        let animationFrame = 0;
        let isVisible = true;
        let isInViewport = true;

        const spacing = 36;
        const effectRadius = 150;
        const maxRepulsion = 22;

        function buildDots() {
            dots.length = 0;

            for (let x = -spacing; x <= width + spacing; x += spacing) {
                for (let y = -spacing; y <= height + spacing; y += spacing) {
                    const offset = Math.sin(x * 0.015) * 4 + Math.cos(y * 0.012) * 4;
                    dots.push({
                        originX: x + offset,
                        originY: y - offset,
                        x: x + offset,
                        y: y - offset,
                        phase: x * 0.012 + y * 0.018,
                        waveStrength: 2.4 + ((x + y) % 5) * 0.18,
                    });
                }
            }
        }

        function resize() {
            const rect = canvas.getBoundingClientRect();
            width = rect.width;
            height = rect.height;
            canvas.width = Math.floor(width * 2);
            canvas.height = Math.floor(height * 2);
            context.setTransform(2, 0, 0, 2, 0, 0);
            buildDots();
        }

        function isInside(clientX, clientY) {
            const rect = canvas.getBoundingClientRect();
            return clientX >= rect.left && clientX <= rect.right && clientY >= rect.top && clientY <= rect.bottom;
        }

        function setPointer(clientX, clientY) {
            const rect = canvas.getBoundingClientRect();
            pointer.x = clientX - rect.left;
            pointer.y = clientY - rect.top;
            pointer.clientX = clientX;
            pointer.clientY = clientY;
            pointer.active = true;
        }

        function clearPointer() {
            pointer.active = false;
            pointer.x = -9999;
            pointer.y = -9999;
        }

        function handlePointer(event) {
            if (!isInside(event.clientX, event.clientY)) {
                clearPointer();
                return;
            }

            setPointer(event.clientX, event.clientY);
        }

        function handleScroll() {
            if (!pointer.active) return;

            if (!isInside(pointer.clientX, pointer.clientY)) {
                clearPointer();
                return;
            }

            setPointer(pointer.clientX, pointer.clientY);
        }

        function stopAnimation() {
            if (animationFrame) {
                window.cancelAnimationFrame(animationFrame);
                animationFrame = 0;
            }
        }

        function startAnimation() {
            if (!animationFrame && isVisible && isInViewport) {
                animationFrame = window.requestAnimationFrame(animate);
            }
        }

        function animate() {
            animationFrame = 0;
            if (!isVisible || !isInViewport) return;

            context.clearRect(0, 0, width, height);
            time += 0.003;

            for (const dot of dots) {
                const waveX = Math.sin(time * 3 + dot.phase) * dot.waveStrength;
                const waveY = Math.cos(time * 2.5 + dot.phase * 1.15) * dot.waveStrength;
                let targetX = dot.originX + waveX;
                let targetY = dot.originY + waveY;
                let intensity = 0;

                if (pointer.active) {
                    const dx = targetX - pointer.x;
                    const dy = targetY - pointer.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < effectRadius) {
                        intensity = 1 - distance / effectRadius;
                        intensity = intensity * intensity;
                        const angle = Math.atan2(dy, dx);
                        targetX += Math.cos(angle) * intensity * maxRepulsion;
                        targetY += Math.sin(angle) * intensity * maxRepulsion;
                    }
                }

                dot.x += (targetX - dot.x) * 0.09;
                dot.y += (targetY - dot.y) * 0.09;

                const idlePulse = 0.04 * Math.sin(time * 2.8 + dot.phase);
                const opacity = Math.min(0.56, 0.2 + idlePulse + intensity * 0.28);
                const radius = 1.35 + idlePulse * 2 + intensity * 1.05;
                const shade = Math.floor(112 + intensity * 30);

                context.beginPath();
                context.arc(dot.x, dot.y, radius, 0, Math.PI * 2);
                context.fillStyle = `rgba(${shade - 6}, ${shade - 8}, ${shade - 14}, ${opacity})`;
                context.fill();
            }

            animationFrame = window.requestAnimationFrame(animate);
        }

        resize();
        startAnimation();

        const visibilityObserver = new IntersectionObserver(([entry]) => {
            isInViewport = entry.isIntersecting;
            if (!isInViewport) {
                stopAnimation();
                return;
            }
            startAnimation();
        }, { threshold: 0.05 });

        visibilityObserver.observe(canvas);

        function handleVisibility() {
            isVisible = !document.hidden;
            if (!isVisible) {
                stopAnimation();
                return;
            }
            startAnimation();
        }

        window.addEventListener('resize', resize);
        window.addEventListener('pointermove', handlePointer, { passive: true });
        window.addEventListener('scroll', handleScroll, { passive: true });
        document.addEventListener('visibilitychange', handleVisibility);

        return () => {
            stopAnimation();
            visibilityObserver.disconnect();
            window.removeEventListener('resize', resize);
            window.removeEventListener('pointermove', handlePointer);
            window.removeEventListener('scroll', handleScroll);
            document.removeEventListener('visibilitychange', handleVisibility);
        };
    }, []);

    return (
        <canvas
            ref={canvasRef}
            className="absolute inset-0 z-0 size-full"
            aria-hidden="true"
        />
    );
}
