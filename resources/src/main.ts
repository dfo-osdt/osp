import { createApp } from 'vue'
import App from './App.vue'
import { installI18n } from './plugins/i18n'
import { installPinia } from './plugins/pinia'
import { installQuasar } from './plugins/quasar'
import { installRouter, Router } from './plugins/router'

import { useAuthStore } from './stores/AuthStore'

const myApp = createApp(App)

installQuasar(myApp)
installI18n(myApp)
installPinia(myApp)
installRouter(myApp)

// Global navigation guard
const authStore = useAuthStore()

Router.beforeEach(async (to) => {
  // check if pages requires auth
  if (authStore.loading) {
    await authStore.waitForLoading()
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      // redirect to login page
      return {
        name: 'login',
        query: { redirect: to.fullPath },
      }
    }
  }
})

// Attempt to handle dynamic imports failing
// https://github.com/vitejs/vite/issues/11804#issuecomment-1406182566
Router.onError((error, to) => {
  if (error.message.includes('Failed to fetch dynamically imported module')) {
    window.location.href = to.fullPath
  }
})

myApp.mount('#app')
