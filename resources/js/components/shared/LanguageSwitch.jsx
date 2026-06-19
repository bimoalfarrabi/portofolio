import { useEffect, useState } from 'react';

/**
 * LanguageSwitch — bracket instrument style: [ACTIVE] → [TARGET]
 * Reads current locale from window.__LOCALE__ set by Blade.
 */
export default function LanguageSwitch({ className = '' }) {
    const [locale, setLocale] = useState(() => window.__LOCALE__ ?? 'id');

    useEffect(() => {
        setLocale(window.__LOCALE__ ?? 'id');
    }, []);

    function switchLocale() {
        const current = locale;
        const pathname = window.location.pathname;
        const search = window.location.search;

        let newPath;
        if (current === 'id') {
            newPath = '/en' + (pathname === '/' ? '' : pathname);
        } else {
            newPath = pathname.replace(/^\/en/, '') || '/';
        }

        window.location.href = newPath + search;
    }

    const isId = locale === 'id';

    return (
        <button
            type="button"
            onClick={switchLocale}
            aria-label={`Switch language to ${isId ? 'EN' : 'ID'}`}
            className={`group inline-flex items-center border border-line bg-surface-1 px-2.5 py-1 font-mono text-[10px] uppercase tracking-[0.18em] transition-all duration-200 hover:border-accent/60 hover:bg-surface-2 active:scale-[0.97] ${className}`}
        >
            {/* ID label */}
            <span className={isId ? 'text-accent' : 'text-ink-mute'}>
                ID
            </span>

            {/* Arrow — points toward active locale, vertically centered */}
            <span className="mx-1.5 -translate-y-px text-sm text-ink-faint transition-colors duration-200 group-hover:text-ink-mute">
                {isId ? '←' : '→'}
            </span>

            {/* EN label */}
            <span className={!isId ? 'text-accent' : 'text-ink-mute'}>
                EN
            </span>
        </button>
    );
}
