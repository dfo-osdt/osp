import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: () => import('@/layouts/MainLayout.vue'),
        children: [
            { path: '', component: () => import('@/pages/IndexPage.vue') },
            {
                path: 'auth/login',
                name: 'login',
                component: () => import('@/pages/auth/LoginPage.vue'),
            },
            {
                path: '/dashboard',
                component: () => import('@/pages/DashboardPage.vue'),
            },
        ],
    },
    {
        path: '/:catchAll(.*)*',
        component: () => import('@/pages/ErrorNotFound.vue'),
    },
];

export default routes;
