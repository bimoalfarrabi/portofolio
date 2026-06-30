/**
 * useTranslation — returns { t, locale } from window.__LOCALE__.
 */
import idTranslations from '../../lang/id.json';
import enTranslations from '../../lang/en.json';

const translations = { id: idTranslations, en: enTranslations };

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
