import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import { BrandIcon } from '../shared';
import { useTranslation } from '../../hooks/useLocale';

export default function ProjectModal({ project, tone, onClose }) {
    const isDark = tone === 'dark';
    const { t } = useTranslation();
    const [shareStatus, setShareStatus] = useState('idle');

    const shareUrl = useMemo(() => {
        if (typeof window === 'undefined' || !project?.id) return null;
        const origin = window.location?.origin ?? '';
        return origin ? `${origin}/p/${project.id}` : null;
    }, [project?.id]);

    const images = useMemo(() => {
        const list = [];
        if (project?.image) list.push(project.image);
        if (Array.isArray(project?.gallery)) {
            project.gallery.forEach((src) => {
                if (src) list.push(src);
            });
        }
        return Array.from(new Set(list));
    }, [project?.image, project?.gallery]);

    const hasSlider = images.length > 1;
    const [slide, setSlide] = useState(0);
    const [direction, setDirection] = useState(0);
    const SWIPE_THRESHOLD = 60;
    const [isPortrait, setIsPortrait] = useState(false);
    const imgRef = useRef(null);

    useEffect(() => {
        setSlide(0);
        setDirection(0);
        setIsPortrait(false);
    }, [project?.id]);

    const handleImageLoad = useCallback((e) => {
        const { naturalWidth, naturalHeight } = e.currentTarget;
        setIsPortrait(naturalHeight > naturalWidth);
    }, []);

    const goTo = (index, dir = null) => {
        if (images.length === 0) return;
        const next = (index + images.length) % images.length;
        setDirection(dir ?? (next > slide ? 1 : next < slide ? -1 : 0));
        setSlide(next);
    };

    const paginate = (dir) => goTo(slide + dir, dir);

    useEffect(() => {
        const handleKeyDown = (event) => {
            if (event.key === 'Escape') {
                onClose();
                return;
            }
            if (!hasSlider) return;
            if (event.key === 'ArrowRight') paginate(1);
            else if (event.key === 'ArrowLeft') paginate(-1);
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [onClose, hasSlider, images.length]);

    useEffect(() => {
        if (shareStatus === 'idle') return undefined;
        const id = window.setTimeout(() => setShareStatus('idle'), 1800);
        return () => window.clearTimeout(id);
    }, [shareStatus]);

    const handleShare = async () => {
        if (!shareUrl) return;
        const title = project.name ?? 'Project';
        const text = project.description ?? '';

        if (navigator.share) {
            try {
                await navigator.share({ title, text, url: shareUrl });
                setShareStatus('shared');
                return;
            } catch (error) {
                if (error?.name === 'AbortError') return;
            }
        }

        try {
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(shareUrl);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = shareUrl;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
            setShareStatus('copied');
        } catch (error) {
            setShareStatus('error');
        }
    };

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[100] flex items-center justify-center px-4 py-6"
        >
            <motion.button
                type="button"
                initial={{ backgroundColor: 'rgba(9, 9, 11, 0)' }}
                animate={{ backgroundColor: 'rgba(9, 9, 11, 0.5)' }}
                exit={{ backgroundColor: 'rgba(9, 9, 11, 0)' }}
                transition={{ duration: 0.42, ease: [0.16, 1, 0.3, 1] }}
                className="absolute inset-0"
                onClick={onClose}
                aria-label="Tutup modal proyek"
            />
            <motion.div
                initial={{ opacity: 0, scale: 0.96, y: 18 }}
                animate={{ opacity: 1, scale: 1, y: 0 }}
                exit={{ opacity: 0, scale: 0.97, y: 12 }}
                transition={{ duration: 0.48, ease: [0.16, 1, 0.3, 1] }}
                className={`relative z-10 w-full max-w-5xl overflow-hidden border shadow-[0_24px_80px_rgba(21,20,15,0.22)] ${
                    isDark ? 'border-white/15 bg-[#15140F] text-surface-1' : 'border-line-strong bg-surface-1 text-ink'
                }`}
            >
                <div className={`pointer-events-none absolute inset-0 ${isDark ? 'bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:28px_28px] opacity-40' : 'grid-hairline-soft opacity-60'}`} />
                <div className={`relative flex items-center justify-between border-b px-4 py-3 ${isDark ? 'border-white/12 bg-white/[0.03]' : 'border-line bg-surface-2'}`}>
                    <div className="flex items-center gap-3">
                        <div className="h-4 w-px bg-line" />
                        <p className={`font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>SYS_MODAL_DESC</p>
                    </div>
                    <div className="flex items-center gap-2 sm:gap-3">
                        <p className={`hidden sm:block font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>SEC: OMEGA</p>
                        {shareUrl && (
                            <button
                                type="button"
                                onClick={handleShare}
                                aria-label="Bagikan project"
                                className={`group inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium transition-colors ${isDark ? 'border-white/10 bg-white/5 text-zinc-200 hover:bg-white/10' : 'border-line bg-surface-1 text-ink-soft hover:border-ink'}`}
                            >
                                {shareStatus === 'copied' || shareStatus === 'shared' ? (
                                    <svg className="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                ) : (
                                    <svg className="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                        <circle cx="18" cy="5" r="3" />
                                        <circle cx="6" cy="12" r="3" />
                                        <circle cx="18" cy="19" r="3" />
                                        <line x1="8.6" y1="13.5" x2="15.4" y2="17.5" />
                                        <line x1="15.4" y1="6.5" x2="8.6" y2="10.5" />
                                    </svg>
                                )}
                                <span>
                                    {shareStatus === 'copied' ? 'Tersalin' : shareStatus === 'shared' ? 'Terbagikan' : shareStatus === 'error' ? 'Gagal' : 'Share'}
                                </span>
                            </button>
                        )}
                        <button
                            type="button"
                            onClick={onClose}
                            aria-label="Tutup"
                            className={`cursor-pointer rounded-full border px-3 py-1.5 text-xs font-medium transition-colors ${isDark ? 'border-white/10 bg-white/5 text-zinc-200 hover:bg-white/10' : 'border-line bg-surface-1 text-ink-soft hover:border-ink'}`}
                        >
                            X
                        </button>
                    </div>
                </div>

                <div className="relative max-h-[82vh] overflow-y-auto p-3 sm:p-4 lg:p-5">
                    <div className={`overflow-hidden border ${isDark ? 'border-white/12 bg-black/20' : 'border-line bg-surface-2'}`}>
                        <div className={`relative overflow-hidden border-b ${isDark ? 'border-white/12 bg-black/30' : 'border-line bg-surface-2'}`}>
                            {images.length === 0 ? (
                                <div className={`flex aspect-[21/9] w-full items-center justify-center bg-surface-2 grid-hairline-soft ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>
                                    <div className="flex flex-col items-center gap-2 text-center">
                                        <span className={`flex size-12 items-center justify-center border ${isDark ? 'border-white/15 bg-white/5' : 'border-line bg-surface-1'}`}>
                                            <svg className="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 19.5h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                                            </svg>
                                        </span>
                                        <p className="font-mono text-[10px] uppercase tracking-[0.28em]">No cover image</p>
                                    </div>
                                </div>
                            ) : (
                                <div className="relative" role={hasSlider ? 'group' : undefined} aria-roledescription={hasSlider ? 'carousel' : undefined}>
                                    <div className={`relative w-full overflow-hidden ${isPortrait ? 'h-[60vh]' : 'aspect-[21/9]'}`}>
                                        <AnimatePresence initial={false} custom={direction}>
                                            <motion.img
                                                key={images[slide]}
                                                src={images[slide]}
                                                alt={`${project.name} ${hasSlider ? slide + 1 : ''}`.trim()}
                                                loading={slide === 0 ? 'eager' : 'lazy'}
                                                decoding="async"
                                                custom={direction}
                                                variants={{
                                                    enter: (dir) => ({ opacity: 0, x: dir > 0 ? '14%' : dir < 0 ? '-14%' : 0, scale: 1.01 }),
                                                    center: { opacity: 1, x: 0, scale: 1 },
                                                    exit: (dir) => ({ opacity: 0, x: dir > 0 ? '-14%' : dir < 0 ? '14%' : 0, scale: 1.01 }),
                                                }}
                                                initial="enter"
                                                animate="center"
                                                exit="exit"
                                                transition={{ duration: 0.34, ease: [0.16, 1, 0.3, 1] }}
                                                ref={imgRef}
                                                onLoad={handleImageLoad}
                                                className={`absolute inset-0 h-full w-full ${isPortrait ? 'object-contain' : 'object-cover'}`}
                                                draggable={false}
                                                drag={hasSlider ? 'x' : false}
                                                dragConstraints={{ left: 0, right: 0 }}
                                                dragElastic={0.18}
                                                onDragEnd={(event, info) => {
                                                    if (!hasSlider) return;
                                                    if (info.offset.x < -SWIPE_THRESHOLD || info.velocity.x < -500) {
                                                        paginate(1);
                                                    } else if (info.offset.x > SWIPE_THRESHOLD || info.velocity.x > 500) {
                                                        paginate(-1);
                                                    }
                                                }}
                                                style={hasSlider ? { cursor: 'none', touchAction: 'pan-y' } : undefined}
                                                whileTap={hasSlider ? { cursor: 'none' } : undefined}
                                                data-cursor={hasSlider ? 'grab' : undefined}
                                            />
                                        </AnimatePresence>
                                    </div>

                                    {hasSlider && (
                                        <>
                                            <button
                                                type="button"
                                                onClick={() => paginate(-1)}
                                                aria-label="Gambar sebelumnya"
                                                className={`absolute left-3 top-1/2 -translate-y-1/2 rounded-full border p-2 backdrop-blur transition ${isDark ? 'border-white/10 bg-zinc-950/55 text-zinc-100 hover:bg-zinc-950/75' : 'border-line bg-surface-1 text-ink-soft hover:border-ink'}`}
                                            >
                                                <svg className="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                                    <polyline points="15 18 9 12 15 6" />
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => paginate(1)}
                                                aria-label="Gambar berikutnya"
                                                className={`absolute right-3 top-1/2 -translate-y-1/2 rounded-full border p-2 backdrop-blur transition ${isDark ? 'border-white/10 bg-zinc-950/55 text-zinc-100 hover:bg-zinc-950/75' : 'border-line bg-surface-1 text-ink-soft hover:border-ink'}`}
                                            >
                                                <svg className="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                                    <polyline points="9 18 15 12 9 6" />
                                                </svg>
                                            </button>

                                            <div className={`absolute right-3 top-3 rounded-full border px-2.5 py-0.5 font-mono text-[10px] uppercase tracking-[0.22em] backdrop-blur ${isDark ? 'border-white/10 bg-zinc-950/55 text-zinc-100' : 'border-line bg-surface-1 text-ink-soft'}`}>
                                                {slide + 1} / {images.length}
                                            </div>

                                            <div className="absolute inset-x-0 bottom-3 flex justify-center gap-1.5">
                                                {images.map((src, idx) => (
                                                    <button
                                                        key={src}
                                                        type="button"
                                                        onClick={() => goTo(idx)}
                                                        aria-label={`Ke gambar ${idx + 1}`}
                                                        className={`h-1.5 rounded-full transition-all ${slide === idx ? (isDark ? 'w-5 bg-white' : 'w-5 bg-ink') : (isDark ? 'w-1.5 bg-white/40 hover:bg-white/70' : 'w-1.5 bg-ink/30 hover:bg-ink/60')}`}
                                                    />
                                                ))}
                                            </div>
                                        </>
                                    )}
                                </div>
                            )}

                            {hasSlider && (
                                <div className={`flex items-center gap-2 overflow-x-auto px-3 py-2 ${isDark ? 'bg-black/35' : 'bg-surface-2'}`}>
                                    {images.map((src, idx) => (
                                        <button
                                            key={`${src}-thumb`}
                                            type="button"
                                            onClick={() => goTo(idx)}
                                            aria-label={`Pilih gambar ${idx + 1}`}
                                            className={`relative h-12 w-16 shrink-0 overflow-hidden rounded-md border transition ${slide === idx ? (isDark ? 'border-white/80' : 'border-ink') : (isDark ? 'border-white/10 hover:border-white/40' : 'border-line hover:border-ink')}`}
                                        >
                                            <img src={src} alt="" loading="lazy" className="h-full w-full object-cover" draggable={false} />
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>

                        <div className="space-y-3 p-3 sm:p-4">
                            <div className={`border p-4 ${isDark ? 'border-white/12 bg-white/[0.03]' : 'border-line bg-surface-1'}`}>
                                <div className="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <div className="flex flex-wrap items-center gap-2">
                                            <div className={`inline-flex border px-3 py-1 text-[10px] font-medium uppercase tracking-[0.22em] ${isDark ? 'border-white/15 bg-white/[0.05] text-zinc-200' : 'border-line bg-surface-2 text-ink-soft'}`}>
                                                STATUS: ACTIVE
                                            </div>
                                            {project.web_url && (
                                                <a
                                                    href={project.web_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className={`inline-flex items-center gap-1.5 border px-3 py-1 text-[10px] font-medium uppercase tracking-[0.22em] transition-colors ${
                                                        isDark
                                                            ? 'border-white/30 bg-white/10 text-zinc-100 hover:border-white/60 hover:bg-white/20'
                                                            : 'border-ink bg-ink text-surface-1 hover:bg-accent hover:border-accent'
                                                    }`}
                                                >
                                                    <svg className="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="2" y1="12" x2="22" y2="12" />
                                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                                    </svg>
                                                    Website
                                                </a>
                                            )}
                                            {project.type === 'open' && Array.isArray(project.repo_urls) && project.repo_urls.map((repo, idx) => (
                                                <a
                                                    key={idx}
                                                    href={repo.url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className={`inline-flex items-center gap-1.5 border px-3 py-1 text-[10px] font-medium uppercase tracking-[0.22em] transition-colors ${
                                                        isDark
                                                            ? 'border-white/30 bg-white/10 text-zinc-100 hover:border-white/60 hover:bg-white/20'
                                                            : 'border-ink bg-ink text-surface-1 hover:bg-accent hover:border-accent'
                                                    }`}
                                                >
                                                    <svg className="size-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z" />
                                                    </svg>
                                                    {repo.label || 'Repository'}
                                                </a>
                                            ))}
                                        </div>
                                        <h3 className="mt-4 text-2xl font-semibold tracking-[-0.04em] sm:text-3xl">{project.name}</h3>
                                        <p className={`mt-2 font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>
                                            {project.category} / {project.year}
                                        </p>
                                    </div>

                                    <div className="grid min-w-[10rem] gap-2 text-sm sm:grid-cols-2">
                                        <div className={`border px-3 py-2 ${isDark ? 'border-white/12 bg-black/20' : 'border-line bg-surface-2'}`}>
                                            <span className={`block font-mono text-[10px] uppercase tracking-[0.24em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>Year</span>
                                            <span className="mt-1 block text-[0.95rem] leading-5">{project.year}</span>
                                        </div>
                                        <div className={`border px-3 py-2 ${isDark ? 'border-white/12 bg-black/20' : 'border-line bg-surface-2'}`}>
                                            <span className={`block font-mono text-[10px] uppercase tracking-[0.24em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>Category</span>
                                            <span className="mt-1 block text-[0.95rem] leading-5">{project.category}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className={`border p-4 ${isDark ? 'border-white/12 bg-white/[0.03]' : 'border-line bg-surface-1'}`}>
                                <p className={`font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>PROTOCOL_DESCRIPTION</p>
                                <p className="mt-3 text-sm leading-7 text-inherit">{project.description}</p>
                            </div>

                            <div className={`overflow-hidden border ${isDark ? 'border-white/12 bg-white/[0.03]' : 'border-line bg-surface-1'}`}>
                                <div className={`flex flex-wrap items-center justify-between gap-3 border-b p-3.5 ${isDark ? 'border-white/12' : 'border-line'}`}>
                                    <div>
                                        <p className={`font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>TECHNICAL STRATEGY</p>
                                        <h4 className="mt-2 text-lg font-semibold tracking-[-0.035em]">{t('modal.strategy.headline')}</h4>
                                    </div>
                                    <span className={`border px-3 py-1 text-xs font-medium ${isDark ? 'border-white/12 bg-white/[0.04] text-zinc-200' : 'border-line bg-surface-2 text-ink-soft'}`}>
                                        {project.stack.length} stack
                                    </span>
                                </div>

                                <div className="grid gap-3.5 p-3.5 lg:grid-cols-[1fr_0.9fr]">
                                    <div className="space-y-4">
                                        <div>
                                            <p className={`font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>APPROACH</p>
                                            <p className="mt-2.5 text-sm leading-7 text-inherit">{project.approach}</p>
                                        </div>
                                        <div className={`border p-3.5 ${isDark ? 'border-success/40 bg-success/10' : 'border-success/40 bg-success-soft/40'}`}>
                                            <p className={`font-mono text-[10px] uppercase tracking-[0.24em] ${isDark ? 'text-success' : 'text-success-deep'}`}>OUTCOME</p>
                                            <p className={`mt-2.5 text-sm leading-7 ${isDark ? 'text-zinc-100' : 'text-ink-soft'}`}>{project.outcome}</p>
                                        </div>
                                    </div>

                                    <div className={`border p-3.5 ${isDark ? 'border-white/12 bg-black/25' : 'border-line bg-surface-2'}`}>
                                        <p className={`font-mono text-[10px] uppercase tracking-[0.28em] ${isDark ? 'text-zinc-500' : 'text-ink-mute'}`}>TECH STACK</p>
                                        <div className="mt-2.5 flex flex-wrap gap-2">
                                            {project.stack.map((item, index) => (
                                                <span
                                                    key={item}
                                                    className={`inline-flex items-center gap-1.5 border px-3 py-1 text-xs font-medium ${
                                                        isDark
                                                            ? index < 3
                                                                ? 'border-white/15 bg-white/10 text-zinc-50'
                                                                : 'border-white/10 bg-white/5 text-zinc-200'
                                                            : index < 3
                                                                ? 'border-ink bg-ink text-surface-1'
                                                                : 'border-line bg-surface-1 text-ink-soft'
                                                    }`}
                                                >
                                                    <BrandIcon name={item} className="size-3.5" />
                                                    {item}
                                                </span>
                                            ))}
                                        </div>
                                        <p className={`mt-3 text-sm leading-7 ${isDark ? 'text-zinc-400' : 'text-ink-mute'}`}>
                                            {t('modal.stack.note')}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </motion.div>
        </motion.div>
    );
}
