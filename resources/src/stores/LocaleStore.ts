import { i18n } from '@/plugins/i18n'
// locale store is used to store the current locale
import { useQuasar } from 'quasar'

export type Locale = 'en' | 'fr'

export const useLocaleStore = defineStore('LocaleStore', () => {
  const locale = ref<'en' | 'fr'>(i18n.global.locale as 'en' | 'fr')
  // this should already be set by the initializer or a previous visit to the site
  const persistentLocale = useStorage('locale', 'en', localStorage)

  const langs = import.meta.glob(
    '../../../node_modules/quasar/lang/(fr|en-US).js',
  )

  const $q = useQuasar()
  // sync initial states i18n, quasar and persistent locale
  setQuasarLocale()

  // what is the other locale?
  const otherLocale = computed(() => {
    return locale.value === 'en' ? 'fr' : 'en'
  })

  function toggleLocale() {
    locale.value = otherLocale.value
    persistentLocale.value = locale.value
    setQuasarLocale()
  }

  function setQuasarLocale() {
    const l = locale.value === 'fr' ? 'fr' : 'en-US'
    langs[`../../../node_modules/quasar/lang/${l}.js`]().then((lang: any) => {
      $q.lang.set(lang.default)
    })
  }

  function isFr(): boolean {
    return locale.value === 'fr'
  }

  return {
    toggleLocale,
    otherLocale,
    locale,
    isFr,
    persistentLocale,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useLocaleStore, import.meta.hot))
