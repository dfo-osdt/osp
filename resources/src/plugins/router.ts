import { createRouter, createWebHashHistory } from 'vue-router';
import { App } from 'vue';

import routes from '@/router/routes';

export const Router = createRouter({
    scrollBehavior(to, from, savedPosition) {
        // Returning the savedPosition will result in a direct jump to the saved position
        if (to.hash) {
            // Check for a hash in the incoming route
            return new Promise((resolve) => {
                // Wait until the next tick to ensure the page has rendered
                setTimeout(() => {
                    const element = document.querySelector(to.hash);
                    if (element) {
                        resolve({
                            el: element,
                            behavior: 'smooth',
                        });
                    } else {
                        // Fallback to top of the page if the element is not found
                        resolve({ top: 0 });
                    }
                }, 250); // Adjust delay as needed
            });
        }
        console.log('savedPosition', savedPosition);
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
