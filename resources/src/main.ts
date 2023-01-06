import { createApp } from 'vue';
import { installQuasar } from './plugins/quasar';
import { installI18n } from './plugins/i18n';
import { installPinia } from './plugins/pinia';
import { installRouter, Router } from './plugins/router';
import { useAuthStore } from './stores/AuthStore';

import App from './App.vue';

const myApp = createApp(App);

installQuasar(myApp);
installI18n(myApp);
installPinia(myApp);
installRouter(myApp);

// Global navigation guard
const authStore = useAuthStore();
Router.beforeEach((to, from, next) => {
    // check if pages requires auth
    if (authStore.loading) return next();

    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (authStore.isAuthenticated) {
            next();
            return;
        } else {
            // redirect to login page
            next({
                name: 'login',
                query: { redirect: to.fullPath },
            });
            return;
        }
    }
    next();
});

myApp.mount('#app');
