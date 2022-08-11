import { createI18n } from 'vue-i18n';
import { App } from 'vue';
import { useStorage } from '@vueuse/core';

/*
 * All i18n resources specified in the plugin `include` option can be loaded
 * at once using the import syntax
 */
import messages from '@intlify/vite-plugin-vue-i18n/messages';

// if it's the users' first visit, set the locale to the one they have set in their browser
const browserLocale = navigator.language.split('-')[0];
const allowedLocales = ['en', 'fr'];
const defaultLocale = allowedLocales.includes(browserLocale)
    ? browserLocale
    : 'en';

const locale = useStorage('locale', defaultLocale, localStorage, {
    mergeDefaults: true,
});

export const i18n = createI18n({
    legacy: false,
    // inject the locale functions into every component
    globalInjection: true,
    locale: locale.value,
    messages,
});

export const installI18n = (app: App<Element>) => {
    app.use(i18n);
};
