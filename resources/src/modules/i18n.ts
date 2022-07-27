import { createI18n } from 'vue-i18n';
import { App } from 'vue';
import { useStorage } from '@vueuse/core';

const messages = Object.fromEntries(
    Object.entries(
        import.meta.glob<{ default: any }>('../locales/*.json', {
            eager: true,
        })
    ).map(([key, value]) => {
        return [key.slice(11, -5), value.default];
    })
);

const browserLocale = navigator.language.split('-')[0];
const allowedLocales = ['en', 'fr'];
const defaultLocale = allowedLocales.includes(browserLocale)
    ? browserLocale
    : 'en';

const locale = useStorage('locale', defaultLocale, localStorage, {
    mergeDefaults: true,
});

export const installI18n = (app: App<Element>) => {
    const i18n = createI18n({
        legacy: false,
        locale: locale.value,
        messages,
    });

    app.use(i18n);
};
