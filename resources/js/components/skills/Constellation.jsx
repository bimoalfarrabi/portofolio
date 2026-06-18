import { motion } from 'motion/react';
import { ConstellationOrbit } from './';

export default function Constellation({ skills }) {
    const activeSkills = (skills ?? []).map((skill, index) => ({
        label: skill.name,
        icon: skill.icon ?? null,
        delay: index * 0.06,
    }));

    return (
        <section id="skills" className="relative overflow-hidden bg-surface-0 px-5 py-24 text-ink">
            <div className="relative mx-auto grid max-w-6xl gap-12 lg:grid-cols-[0.86fr_1.14fr] lg:items-center">
                <motion.div
                    data-reveal
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-80px' }}
                    transition={{ duration: 0.8, ease: [0.16, 1, 0.3, 1] }}
                >
                    <p className="eng-label mb-3">SYS_NODES · 05</p>
                    <h2 className="max-w-xl text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
                        Skills, projects, and tools
                        <span className="block text-ink-faint">connected as one <span className="text-accent">system</span>.</span>
                    </h2>
                    <p className="mt-6 max-w-lg text-base leading-8 text-ink-mute">
                        Bukan bio panjang, bukan CV keras. Cuma peta singkat yang nunjukin apa yang saling terhubung di cara kerja saya.
                    </p>
                </motion.div>

                <ConstellationOrbit nodes={activeSkills} />
            </div>
        </section>
    );
}
