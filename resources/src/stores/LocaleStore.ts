// locale store is used to store the current locale
import { i18n } from '@/plugins/i18n';
import { Quasar, QuasarLanguage } from 'quasar';
import { langs } from '@/plugins/quasar';

export const useLocaleStore = defineStore('LocaleStore', () => {
    const locale = ref(i18n.global.locale);
    // this should already be set by the initializer or a previous visit to the site
    const persistentLocale = useStorage('locale', 'en', localStorage);

    // sync initial states i18n, quasar and persistent locale
    setQuasarLocale();

    function toggleLocale() {
        locale.value = otherLocale.value;
        persistentLocale.value = locale.value;
        setQuasarLocale();
    }

    function setQuasarLocale() {
        const l = locale.value === 'fr' ? 'fr' : 'en-US';
        langs[`../../../node_modules/quasar/lang/${l}.mjs`]().then((lang) => {
            Quasar.lang.set(lang.default);
        });
    }

    // what is the other locale?
    const otherLocale = computed(() => {
        return locale.value === 'en' ? 'fr' : 'en';
    });

    return {
        toggleLocale,
        otherLocale,
        locale,
        persistentLocale,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useLocaleStore, import.meta.hot));
