import { createRouter, createWebHashHistory } from 'vue-router';
import { App } from 'vue';

import routes from '@/router/routes';

export const Router = createRouter({
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    const element = document.querySelector(to.hash);
                    if (element) {
                        resolve({
                            el: element,
                            behavior: 'smooth',
                        });
                    } else {
                        resolve({ top: 0 });
                    }
                }, 250);
            });
        }
        if (savedPosition) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(savedPosition);
                }, 250);
            });
        }
        return { top: 0 };
    },
    routes,
    history: createWebHashHistory(),
});

export const installRouter = (app: App<Element>) => {
    app.use(Router);
};
