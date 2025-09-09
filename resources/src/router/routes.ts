import type { RouteRecordRaw } from 'vue-router'
import UnderConstruction from '@/pages/UnderConstructionPage.vue'

declare module 'vue-router' {
  interface RouteMeta {
    requiresAuth?: boolean
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
          { path: '', redirect: { name: 'login' } },
          {
            path: 'login',
            component: () => import('@/models/auth/components/LoginCard.vue'),
            name: 'login',
            meta: { requiresAuth: false },
          },
          {
            path: 'logout',
            component: () => import('@/models/auth/components/LogoutCard.vue'),
            name: 'logout',
            meta: { requiresAuth: false },
          },
          {
            path: 'register',
            component: () =>
              import('@/models/auth/components/RegisterCard.vue'),
            name: 'register',
            meta: { requiresAuth: false },
          },
          {
            path: 'forgot-password',
            component: () =>
              import('@/models/auth/components/ForgotPasswordCard.vue'),
            name: 'forgotPassword',
            meta: { requiresAuth: false },
          },
          {
            path: 'password-reset',
            component: () =>
              import('@/models/auth/components/ResetPasswordCard.vue'),
            name: 'resetPassword',
            meta: { requiresAuth: false },
          },
          {
            path: 'orcid-callback',
            component: () =>
              import('@/models/auth/components/OrcidCallbackCard.vue'),
            name: 'orcidCallback',
            meta: { requiresAuth: true },
          },
        ],
      },
      {
        path: '/dashboard',
        component: () => import('@/pages/DashboardPage.vue'),
        name: 'dashboard',
        meta: { requiresAuth: true },
      },
      {
        path: '/settings',
        component: () => import('@/pages/SettingsPage.vue'),
        meta: { requiresAuth: true },
        children: [
          { path: '', redirect: { name: 'settings.profile' } },
          {
            path: 'profile',
            component: () => import('@/models/User/views/UserProfileView.vue'),
            name: 'settings.profile',
          },
          {
            path: 'author',
            component: () =>
              import('@/models/Author/views/ManageAuthorProfileView.vue'),
            name: 'settings.author',
          },
          {
            path: 'security',
            component: () =>
              import('@/models/User/views/UserAccountSecurityView.vue'),
            name: 'settings.security',
          },
          {
            path: 'invitations',
            component: () =>
              import('@/models/User/views/UserInvitationsView.vue'),
            name: 'settings.invitations',
          },
        ],
      },
      {
        path: '/my-manuscripts',
        component: () =>
          import('@/models/ManuscriptRecord/views/MyManuscriptRecordsView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/my-publications',
        component: () =>
          import('@/models/Publication/views/MyPublicationsView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/my-reviews',
        component: () =>
          import(
            '@/models/ManagementReviewStep/views/MyManagementReviewStepsView.vue',
          ),
        meta: { requiresAuth: true },
      },
      {
        path: '/publications',
        component: () =>
          import('@/models/Publication/views/PublicationsView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/regional-manuscripts',
        component: () =>
          import(
            '@/models/ManuscriptRecord/views/RegionalManuscriptRecordsView.vue',
          ),
        meta: { requiresAuth: true },
        name: 'regional-manuscripts',
      },
      {
        path: '/authors',
        component: () => import('@/models/Author/views/AuthorsView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/author/:id',
        component: () => import('@/models/Author/views/AuthorProfileView.vue'),
        meta: { requiresAuth: true },
        props: route => ({ id: Number(route.params.id) }),
        name: 'author.profile',
      },
      {
        path: '/preprints',
        component: () => import('@/models/Preprint/views/PreprintListView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: '/sensitive-issues',
        component: UnderConstruction,
        meta: { requiresAuth: true },
      },
      {
        path: '/manuscript/:id',
        component: () =>
          import('@/models/ManuscriptRecord/views/ManuscriptRecordPage.vue'),
        meta: { requiresAuth: true },
        props: route => ({ id: Number(route.params.id) }),
        children: [
          {
            path: '',
            redirect: { name: 'manuscript.form' },
            name: 'manuscript',
          },
          {
            path: 'form',
            component: () =>
              import(
                '@/models/ManuscriptRecord/views/ManuscriptRecordFormView.vue',
              ),
            props: route => ({ id: Number(route.params.id) }),
            name: 'manuscript.form',
          },
          {
            path: 'reviews',
            component: () =>
              import(
                '@/models/ManagementReviewStep/views/ManagementReviewStepsView.vue',
              ),
            props: route => ({ id: Number(route.params.id) }),
            name: 'manuscript.reviews',
          },
          {
            path: 'progress',
            component: () =>
              import(
                '@/models/ManuscriptRecord/views/ManuscriptRecordProgressView.vue',
              ),
            props: route => ({ id: Number(route.params.id) }),
            name: 'manuscript.progress',
          },
          {
            path: 'sharing',
            component: () =>
              import(
                '@/models/ManuscriptRecord/views/ManuscriptRecordSharingView.vue',
              ),
            props: route => ({ id: Number(route.params.id) }),
            name: 'manuscript.sharing',
          },
        ],
      },
      {
        path: '/publication/:id',
        component: () =>
          import('@/models/Publication/views/PublicationPageView.vue'),
        meta: { requiresAuth: true },
        props: route => ({ id: Number(route.params.id) }),
        name: 'publication',
      },
    ],
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('@/pages/ErrorNotFound.vue'),
    name: 'notFound',
  },
  {
    path: '/invalid-signature',
    component: () => import('@/pages/ErrorInvalidSignature.vue'),
    name: 'invalidSignature',
  },
]

export default routes
