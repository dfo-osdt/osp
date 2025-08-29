import type { App } from 'vue'
import { createPinia } from 'pinia'

export function installPinia(app: App<Element>) {
  const pinia = createPinia()
  app.use(pinia)
}
