import { useEffect, useMemo, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import {
    ProjectModal,
    ProjectOrbit,
} from './';
import { useTranslation } from '../../hooks/useLocale';

// Presentation config per mode — tone/label/headline live here, not in the mapper.
const ORBIT_CONFIG = {
    open:   { tone: 'light', activeLabel: 'Public Orbit',    headline: 'OPEN SOURCE'   },
    closed: { tone: 'dark',  activeLabel: 'Classified Orbit', headline: 'CLOSED SOURCE' },
};

function normalizeProjects(seedProjects) {
    const grouped = seedProjects
        .map((project, index) => ({
            id: project.id ?? null,
            name: project.name ?? project.title,
            type: project.type,
            category: project.category,
            year: project.year,
            accent: project.accent ?? (project.type === 'closed' ? 'bg-amber-300' : 'bg-zinc-950'),
            delay: index * 0.08,
            link: project.link ?? '#',
            image: project.image_url ?? project.image ?? null,
            gallery: project.gallery_urls ?? [],
            description: project.description,
            approach: project.approach,
            stack: project.stack ?? [],
            outcome: project.outcome,
            isFeatured: project.is_featured ?? false,
            repo_urls: project.repo_urls ?? [],
            web_url: project.web_url ?? null,
        }))
        .sort((left, right) => {
            // Featured selalu di depan, sisanya urut by year
            if (left.isFeatured !== right.isFeatured) return left.isFeatured ? -1 : 1;
            return left.year - right.year;
        });

    return {
        open:   grouped.filter((p) => p.type !== 'closed'),
        closed: grouped.filter((p) => p.type === 'closed'),
    };
}

export default function OrbitProjects({ projects: seedProjects = [], focusProjectId = null }) {
    const { t } = useTranslation();
    const initialMode = useMemo(() => {
        if (!focusProjectId) return 'open';
        const target = seedProjects.find((p) => Number(p.id) === Number(focusProjectId));
        return target?.type === 'closed' ? 'closed' : 'open';
    }, [seedProjects, focusProjectId]);

    const [mode, setMode] = useState(initialMode);
    const [selectedProject, setSelectedProject] = useState(null);
    const normalized = useMemo(() => normalizeProjects(seedProjects), [seedProjects]);
    const config = ORBIT_CONFIG[mode];
    const projects = normalized[mode];

    const autoOpenedRef = useRef(false);
    useEffect(() => {
        if (!focusProjectId || autoOpenedRef.current) return undefined;
        const target = projects.find((p) => Number(p.id) === Number(focusProjectId));
        if (!target) return undefined;

        autoOpenedRef.current = true;
        const handle = window.setTimeout(() => {
            const section = document.getElementById('projects');
            section?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            setSelectedProject(target);
        }, 450);
        return () => window.clearTimeout(handle);
    }, [projects, focusProjectId]);

    useEffect(() => {
        if (!selectedProject) return undefined;
        const body = document.body;
        const previousOverflow = body.style.overflow;
        body.style.overflow = 'hidden';
        return () => {
            body.style.overflow = previousOverflow;
        };
    }, [selectedProject]);

    return (
        <section id="projects" className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink">

            <motion.div
                animate={selectedProject ? { filter: 'blur(10px)', scale: 0.992 } : { filter: 'blur(0px)', scale: 1 }}
                transition={{ duration: 0.42, ease: [0.16, 1, 0.3, 1] }}
                className="relative mx-auto grid max-w-6xl gap-12 will-change-[filter,transform] lg:grid-cols-[0.88fr_1.12fr] lg:items-center"
            >
                <motion.div
                    data-reveal
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                    className="max-w-xl"
                >
                    <p className="eng-label mb-3">SYS_ORBIT · 06</p>
                    <h2 className="text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
                        {t('orbit.headline')}
                        <span className="block text-ink-faint">{t('orbit.headline.sub')} <span className="text-accent">{t('orbit.headline.accent')}</span>.</span>
                    </h2>
                    <p className="mt-6 max-w-lg text-base leading-8 text-ink-mute">
                        {t('orbit.desc')}
                    </p>

                    <div className="relative mt-8 grid w-[20rem] grid-cols-2 border border-line bg-surface-1 p-1">
                        <motion.div
                            layout
                            transition={{ type: 'spring', stiffness: 420, damping: 34 }}
                            className="absolute top-1 z-0 h-[calc(100%-0.5rem)] w-[calc(50%-0.25rem)] bg-ink"
                            style={{ left: mode === 'open' ? '0.25rem' : '50%' }}
                        />
                        {[
                            { key: 'open', label: 'Open Source' },
                            { key: 'closed', label: 'Closed Source' },
                        ].map((option) => {
                            const active = mode === option.key;
                            return (
                                <button
                                    key={option.key}
                                    type="button"
                                    onClick={() => setMode(option.key)}
                                    className={`group relative z-10 flex cursor-pointer items-center justify-center px-4 py-2 text-xs font-medium uppercase tracking-[0.14em] transition-colors duration-200 ${
                                        active
                                            ? 'text-surface-1'
                                            : 'text-ink-mute hover:text-ink'
                                    }`}
                                >
                                    <span className="relative z-10">{option.label}</span>
                                </button>
                            );
                        })}
                    </div>

                    <div className="mt-4 h-1 overflow-hidden bg-surface-2">
                        <motion.div
                            animate={{ x: mode === 'open' ? '0%' : '100%' }}
                            transition={{ type: 'spring', stiffness: 420, damping: 34 }}
                            className="h-full w-1/2 bg-accent"
                        />
                    </div>
                </motion.div>

                <div>
                    <div className="mb-4 max-w-[18rem] text-ink-mute">
                        <p className="eng-label">{config.headline}</p>
                        <p className="mt-2 text-sm leading-6">{t(`orbit.${mode}.subcopy`)}</p>
                    </div>
                    <AnimatePresence mode="wait">
                        <ProjectOrbit
                            key={mode}
                            projects={projects}
                            tone={config.tone}
                            headline={config.headline}
                            subcopy={t(`orbit.${mode}.subcopy`)}
                            activeLabel={config.activeLabel}
                            direction={mode}
                            onSelectProject={setSelectedProject}
                        />
                    </AnimatePresence>
                </div>
            </motion.div>

            <AnimatePresence>
                {selectedProject && (
                    <ProjectModal
                        project={selectedProject}
                        tone={mode === 'open' ? 'light' : 'dark'}
                        onClose={() => setSelectedProject(null)}
                    />
                )}
            </AnimatePresence>
        </section>
    );
}
