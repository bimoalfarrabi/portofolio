import { useEffect, useRef } from 'react';

// ponytail: no trailing effect, no particle — crosshair reticle only, ~30 lines
export default function NasaCursor() {
    const dotRef = useRef(null);
    const ringRef = useRef(null);

    useEffect(() => {
        // Hide on touch devices — no cursor to replace
        if (window.matchMedia('(pointer: coarse)').matches) return;

        const dot = dotRef.current;
        const ring = ringRef.current;
        let raf;
        let mx = -100, my = -100;
        let rx = -100, ry = -100;

        const onMove = (e) => {
            mx = e.clientX;
            my = e.clientY;

            const isInteractive = e.target.closest('a, button, [role="button"], input, textarea, select, label, [data-cursor="pointer"]');
            ring.dataset.active = isInteractive ? '1' : '0';
        };

        const loop = () => {
            // ring lags slightly behind for soft feel
            rx += (mx - rx) * 0.18;
            ry += (my - ry) * 0.18;

            dot.style.transform = `translate(${mx}px, ${my}px)`;
            ring.style.transform = `translate(${rx}px, ${ry}px)`;
            raf = requestAnimationFrame(loop);
        };

        document.addEventListener('mousemove', onMove);
        raf = requestAnimationFrame(loop);

        return () => {
            document.removeEventListener('mousemove', onMove);
            cancelAnimationFrame(raf);
        };
    }, []);

    return (
        <>
            {/* Centre dot */}
            <div
                ref={dotRef}
                aria-hidden="true"
                className="nasa-cursor-dot"
            />
            {/* Crosshair ring */}
            <div
                ref={ringRef}
                aria-hidden="true"
                className="nasa-cursor-ring"
            />
        </>
    );
}
