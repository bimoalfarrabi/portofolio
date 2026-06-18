import { motion } from 'motion/react';
import { BrandIcon } from '../shared';

const socialLinks = [
    { label: 'Email', href: 'mailto:bimoalfarrabi24@gmail.com' },
    { label: 'LinkedIn', href: 'https://linkedin.com/in/bimoalfarrabi' },
    { label: 'GitHub', href: 'https://github.com/bimoalfarrabi' },
];

const currentYear = new Date().getFullYear();

export default function Footer() {
    return (
        <motion.footer
            data-reveal
            initial={{ opacity: 0, y: 14 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1], delay: 0.3 }}
            className="border-t border-line bg-surface-0"
        >
            <div className="mx-auto flex max-w-7xl flex-col items-center gap-4 px-5 py-6 sm:flex-row sm:justify-between sm:px-8">
                <div className="flex items-center gap-3 font-mono text-xs uppercase tracking-[0.18em] text-ink-mute">
                    <span className="text-ink">viasco<span className="text-ink-faint">.prjkt</span></span>
                    <span className="text-ink-faint">//</span>
                    <span>EOT_{currentYear}</span>
                </div>

                <div className="flex items-center gap-1">
                    {socialLinks.map((link, i) => (
                        <a
                            key={link.label}
                            href={link.href}
                            target={link.href.startsWith('http') ? '_blank' : undefined}
                            rel={link.href.startsWith('http') ? 'noopener noreferrer' : undefined}
                            className={`group inline-flex items-center gap-2 px-3 py-1.5 font-mono text-xs uppercase tracking-[0.16em] text-ink-mute transition-colors duration-200 hover:text-accent ${i > 0 ? 'border-l border-line' : ''}`}
                        >
                            <BrandIcon name={link.label} className="size-3.5 text-ink-faint transition-colors duration-200 group-hover:text-accent" />
                            {link.label}
                        </a>
                    ))}
                </div>
            </div>
        </motion.footer>
    );
}
