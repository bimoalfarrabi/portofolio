import { useEffect, useState } from 'react';
import { AnimatePresence, motion, useMotionValueEvent, useScroll } from 'motion/react';
import { LanguageSwitch } from '../shared';

const navLinks = [
    { label: 'Progress', href: '#progress' },
    { label: 'About', href: '#about' },
    { label: 'Signal', href: '#signal' },
    { label: 'Skills', href: '#skills' },
    { label: 'Projects', href: '#projects' },
    { label: 'Collab', href: '#collab' },
];

export default function Navbar() {
    const [menuOpen, setMenuOpen] = useState(false);
    const [activeLink, setActiveLink] = useState('');
    const [scrolled, setScrolled] = useState(false);
    const [orbitLock, setOrbitLock] = useState(false);
    const { scrollYProgress } = useScroll();

    const scrollToHero = (event) => {
        event.preventDefault();
        setOrbitLock(true);
        document.getElementById('intro')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setMenuOpen(false);
        window.setTimeout(() => setOrbitLock(false), 700);
    };

    useMotionValueEvent(scrollYProgress, 'change', (latest) => {
        setScrolled(latest > 0.01);
    });

    useEffect(() => {
        const ids = navLinks.map((l) => l.href.slice(1));
        const observers = [];

        ids.forEach((id) => {
            const el = document.getElementById(id);
            if (!el) return;
            const observer = new IntersectionObserver(
                ([entry]) => {
                    if (entry.isIntersecting) {
                        setActiveLink(`#${id}`);
                    }
                },
                { rootMargin: '-20% 0px -70% 0px', threshold: 0 },
            );
            observer.observe(el);
            observers.push(observer);
        });

        return () => observers.forEach((o) => o.disconnect());
    }, []);

    return (
        <motion.nav
            initial={{ y: -18, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
            className="fixed inset-x-0 top-0 z-50 border-b border-line bg-surface-0"
        >
            {/* Scroll progress bar */}
            <motion.div
                className="absolute inset-x-0 bottom-0 h-0.5 origin-left bg-accent"
                style={{ scaleX: scrollYProgress }}
            />

            <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-8">
                <motion.a
                    href="#intro"
                    onClick={scrollToHero}
                    className="group inline-flex items-center gap-3"
                    aria-label="Year Progress home"
                >
                    <motion.span
                        animate={orbitLock ? { scale: [1, 1.18, 1], rotate: [0, 10, 0] } : { scale: [1, 1.08, 1] }}
                        whileHover={{ scale: 1.12, rotate: -4 }}
                        transition={orbitLock ? { duration: 0.7, ease: [0.16, 1, 0.3, 1] } : { duration: 3.2, repeat: Infinity, ease: 'easeInOut' }}
                        className="relative flex size-8 items-center justify-center rounded-full border border-line bg-surface-1"
                    >
                        <motion.span
                            animate={orbitLock ? { scale: [1, 1.6, 1], opacity: [1, 0.55, 1] } : { scale: [1, 1.35, 1], opacity: [1, 0.72, 1] }}
                            transition={orbitLock ? { duration: 0.7, ease: [0.16, 1, 0.3, 1] } : { duration: 2.4, repeat: Infinity, ease: 'easeInOut' }}
                            className="size-2 rounded-full bg-accent transition-transform duration-300 group-hover:scale-125"
                        />
                        <motion.span
                            animate={orbitLock ? { rotate: 180 } : { rotate: 360 }}
                            transition={orbitLock ? { duration: 0.7, ease: 'easeOut' } : { duration: 8, repeat: Infinity, ease: 'linear' }}
                            className="absolute inset-1 rounded-full border border-dashed border-line transition-[border-color] duration-300 group-hover:border-ink"
                        />
                        <motion.span
                            animate={orbitLock ? { rotate: -180 } : { rotate: 360 }}
                            transition={orbitLock ? { duration: 0.7, ease: 'easeOut' } : { duration: 6, repeat: Infinity, ease: 'linear' }}
                            className="absolute left-1/2 size-1 rounded-full bg-ink"
                            style={{ top: '2px', marginLeft: -2, transformOrigin: '0 14px' }}
                        />
                    </motion.span>
                    <span className="font-mono text-sm font-semibold uppercase tracking-[0.08em] text-ink">
                        viasco<span className="text-ink-faint">.prjkt</span>
                    </span>
                </motion.a>

                <div className="hidden items-center gap-1 md:flex">
                    <LanguageSwitch className="mr-2" />
                    {navLinks.map((link) => {
                        const isActive = activeLink === link.href;
                        return (
                            <a
                                key={link.label}
                                href={link.href}
                                className={`relative px-4 py-2 font-mono text-xs uppercase tracking-[0.14em] transition-colors duration-200 ${
                                    isActive
                                        ? 'text-ink'
                                        : 'text-ink-mute hover:text-ink'
                                }`}
                            >
                                {isActive && (
                                    <motion.span
                                        layoutId="nav-pill"
                                        className="absolute inset-x-3 -bottom-px h-0.5 bg-accent"
                                        transition={{ type: 'spring', stiffness: 380, damping: 30 }}
                                    />
                                )}
                                <span className="relative z-10">{link.label}</span>
                            </a>
                        );
                    })}
                </div>

                <button
                    type="button"
                    onClick={() => setMenuOpen((open) => !open)}
                    className="flex size-10 items-center justify-center border border-line bg-surface-1 md:hidden"
                    aria-label="Toggle navigation menu"
                    aria-expanded={menuOpen}
                >
                    <span className="relative size-5">
                        <motion.span
                            animate={menuOpen ? { rotate: 45, y: 6 } : { rotate: 0, y: 0 }}
                            className="absolute left-0 top-1 block h-px w-5 bg-ink"
                        />
                        <motion.span
                            animate={menuOpen ? { opacity: 0 } : { opacity: 1 }}
                            className="absolute left-0 top-2.5 block h-px w-5 bg-ink"
                        />
                        <motion.span
                            animate={menuOpen ? { rotate: -45, y: -6 } : { rotate: 0, y: 0 }}
                            className="absolute left-0 top-4 block h-px w-5 bg-ink"
                        />
                    </span>
                </button>
            </div>

            <AnimatePresence>
                {menuOpen && (
                    <motion.div
                        initial={{ height: 0, opacity: 0 }}
                        animate={{ height: 'auto', opacity: 1 }}
                        exit={{ height: 0, opacity: 0 }}
                        transition={{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }}
                        className="border-t border-line bg-surface-0 md:hidden"
                        style={{ overflow: 'hidden' }}
                    >
                        <div className="flex flex-col gap-1 px-5 py-5">
                            <LanguageSwitch className="px-4 py-2" />
                            {navLinks.map((link) => {
                                const isActive = activeLink === link.href;
                                return (
                                    <a
                                        key={link.label}
                                        href={link.href}
                                        onClick={(e) => {
                                            e.preventDefault();
                                            setMenuOpen(false);
                                            const id = link.href.slice(1);
                                            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                        }}
                                        className={`relative px-4 py-2 font-mono text-xs uppercase tracking-[0.14em] transition-colors ${
                                            isActive
                                                ? 'text-ink'
                                                : 'text-ink-mute hover:text-ink'
                                        }`}
                                    >
                                        {isActive && (
                                            <motion.span
                                                layoutId="nav-pill-mobile"
                                                className="absolute left-1 top-1/2 size-1.5 -translate-y-1/2 rounded-full bg-accent"
                                                transition={{ type: 'spring', stiffness: 380, damping: 30 }}
                                            />
                                        )}
                                        <span className="relative z-10">{link.label}</span>
                                    </a>
                                );
                            })}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </motion.nav>
    );
}
