import { useEffect, useState } from 'react';

/**
 * LanguageSwitch — toggle between ID (default) and EN (/en/...).
 * Reads current locale from window.__LOCALE__ set by Blade.
 * On switch: redirects to the equivalent URL in the other locale.
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
            // ID → EN: prepend /en
            newPath = '/en' + (pathname === '/' ? '' : pathname);
        } else {
            // EN → ID: remove /en prefix
            newPath = pathname.replace(/^\/en/, '') || '/';
        }

        window.location.href = newPath + search;
    }

    const targetLang = locale === 'id' ? 'EN' : 'ID';

    return (
        <button
            type="button"
            onClick={switchLocale}
            aria-label={`Switch language to ${targetLang}`}
            className={`inline-flex items-center gap-1.5 font-mono text-[10px] uppercase tracking-[0.2em] text-ink-soft transition-colors hover:text-ink ${className}`}
        >
            <span className="text-ink-mute">{locale.toUpperCase()}</span>
            <span className="text-ink-mute opacity-40">/</span>
            <span className="text-ink">{targetLang}</span>
        </button>
    );
}
