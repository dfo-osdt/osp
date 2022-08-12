import { RouteRecordRaw } from 'vue-router';

declare module 'vue-router' {
    interface RouteMeta {
        requiresAuth?: boolean;
    }
}

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('@/layouts/MainLayout.vue'),
        children: [
            { path: '', component: () => import('@/pages/IndexPage.vue') },
            {
                path: 'auth',
                component: () => import('@/layouts/AuthPageLayout.vue'),
                children: [
                    { path:  '', redirect: { name: 'login' } },
                    { 
                        path: 'login',
                        component: () => import('@/models/auth/components/LoginCard.vue'),
                        name: 'login',
                    },
                ]
            },
            {
                path: '/dashboard',
                component: () => import('@/pages/DashboardPage.vue'),
                name: 'dashboard',
                meta: { requiresAuth: true },
            },
        ],
    },
    {
        path: '/:catchAll(.*)*',
        component: () => import('@/pages/ErrorNotFound.vue'),
    },
];

export default routes;
