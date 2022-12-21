import { createI18n } from 'vue-i18n';
import { App } from 'vue';
import { useStorage } from '@vueuse/core';

import en from '@/locales/en.json';
import fr from '@/locales/fr.json';

type MessageSchema = typeof en;

// if it's the users' first visit, set the locale to the one they have set in their browser
const browserLocale = navigator.language.split('-')[0];
const allowedLocales = ['en', 'fr'];
const defaultLocale = allowedLocales.includes(browserLocale)
    ? browserLocale
    : 'en';

const locale = useStorage('locale', defaultLocale, localStorage, {
    mergeDefaults: true,
});

export const i18n = createI18n<[MessageSchema], 'en' | 'fr'>({
    legacy: false,
    // inject the locale functions into every component
    globalInjection: true,
    fallbackLocale: 'en',
    locale: locale.value,
    messages: {
        en,
        fr,
    },
});

export const installI18n = (app: App<Element>) => {
    app.use(i18n);
};
