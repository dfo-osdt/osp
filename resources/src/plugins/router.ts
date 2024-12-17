import type { App } from 'vue'
import routes from '@/router/routes'

import { createRouter, createWebHashHistory } from 'vue-router'

export const Router = createRouter({
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return new Promise((resolve) => {
        setTimeout(() => {
          const element = document.querySelector(to.hash)
          if (element) {
            resolve({
              el: element,
              behavior: 'smooth',
            })
          }
          else {
            console.warn(`Element with id ${to.hash} not found`)
            resolve({ top: 0 })
          }
        }, 500)
      })
    }
    if (savedPosition) {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve(savedPosition)
        }, 500)
      })
    }
    return { top: 0 }
  },
  routes,
  history: createWebHashHistory(),
})

export function installRouter(app: App<Element>) {
  app.use(Router)
}
