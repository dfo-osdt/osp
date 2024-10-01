import type { App } from 'vue'
import en from '@/locales/en.json'
import fr from '@/locales/fr.json'

import { useStorage } from '@vueuse/core'
import { createI18n } from 'vue-i18n'

type MessageSchema = typeof en

// if it's the users' first visit, set the locale to the one they have set in their browser
const browserLocale = navigator.language.split('-')[0]
const allowedLocales = ['en', 'fr']
const defaultLocale = allowedLocales.includes(browserLocale)
  ? browserLocale
  : 'en'

const locale = useStorage('locale', defaultLocale, localStorage, {
  mergeDefaults: true,
})

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
})

export function installI18n(app: App<Element>) {
  app.use(i18n)
}
