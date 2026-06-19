/**
 * useLocale — returns the current locale ('id' | 'en') from window.__LOCALE__.
 * Also exposes a t() helper that looks up keys from the bundled translations.
 */
import idTranslations from '../../lang/id.json';
import enTranslations from '../../lang/en.json';

const translations = { id: idTranslations, en: enTranslations };

export function useLocale() {
    const locale = window.__LOCALE__ ?? 'id';
    return locale;
}

export function useTranslation() {
    const locale = window.__LOCALE__ ?? 'id';
    const dict = translations[locale] ?? translations.id;

    /**
     * t('key') — returns translated string, falls back to key itself.
     */
    function t(key) {
        return dict[key] ?? idTranslations[key] ?? key;
    }

    return { t, locale };
}
