import { createRouter, createWebHashHistory } from 'vue-router';
import { App } from 'vue';

import routes from '@/router/routes';

export const installRouter = (app: App<Element>) => {
    const Router = createRouter({
        scrollBehavior: () => ({ left: 0, top: 0 }),
        routes,
        history: createWebHashHistory(),
    });

    app.use(Router);
};
