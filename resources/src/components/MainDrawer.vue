<template>
    <q-drawer
        :width="225"
        :mini="authStore.isDrawerMini"
        @mouseover="authStore.isDrawerMini = false"
        @mouseout="authStore.isDrawerMini = true"
    >
        <q-list padding class="menu-list">
            <DrawerMenuItem
                v-for="item in userMenuItems"
                :key="item.label"
                :item="item"
            ></DrawerMenuItem>

            <q-separator></q-separator>
            <q-item-label header>Explore</q-item-label>

            <DrawerMenuItem
                v-for="item in exploreMenuItems"
                :key="item.label"
                :item="item"
            >
            </DrawerMenuItem>
        </q-list>
    </q-drawer>
</template>

<script setup lang="ts">
import { QDrawer, QList, QSeparator, QItemLabel } from 'quasar';
import DrawerMenuItem, { MenuItem } from './DrawerMenuItem.vue';
const authStore = useAuthStore();
const { t } = useI18n();

const userMenuItems = computed<MenuItem[]>(() => {
    return [
        {
            icon: 'mdi-view-dashboard',
            label: t('common.dashboard'),
            to: '/dashboard',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
        {
            icon: 'mdi-file-document-multiple',
            label: t('common.my-manuscripts'),
            to: '/my-manuscripts',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
        {
            icon: 'mdi-newspaper-variant-multiple-outline',
            label: t('common.my-publications'),
            to: '/my-publications',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
        {
            icon: 'mdi-file-sign',
            label: t('common.my-reviews'),
            to: '/my-reviews',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
    ];
});

const exploreMenuItems = computed<MenuItem[]>(() => {
    return [
        {
            icon: 'mdi-newspaper-variant-multiple-outline',
            label: t('common.publications'),
            to: '/publications',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
        {
            icon: 'mdi-account-group-outline',
            label: t('common.authors'),
            to: '/authors',
            visible: true,
            tooltipVisible: authStore.isDrawerMini,
        },
        // {
        //     icon: 'mdi-archive-star-outline',
        //     label: t('common.sensitive-issues'),
        //     to: '/sensitive-issues',
        //     visible: true,
        //     tooltipVisible: authStore.isDrawerMini,
        // },
    ];
});
</script>
