<template>
    <q-drawer :width="225" :mini="authStore.isDrawerOpen">
        <div class="flex column q-my-md">
            <div class="row fit justify-center">
                <q-img src="/assets/logo.svg" width="40%"></q-img>
            </div>
        </div>
        <q-separator></q-separator>
        <q-list padding class="menu-list">
            <q-item
                v-for="item in userMenuItems"
                :key="item.label"
                v-ripple
                clickable
                :to="item.to"
            >
                <q-item-section avatar>
                    <q-icon :name="item.icon" />
                </q-item-section>

                <q-item-section>{{ item.label }}</q-item-section>
            </q-item>

            <q-separator></q-separator>
            <q-item-label header>Explore</q-item-label>

            <q-item
                v-for="item in exploreMenuItems"
                :key="item.label"
                v-ripple
                clickable
                :to="item.to"
            >
                <q-item-section avatar>
                    <q-icon :name="item.icon" />
                </q-item-section>

                <q-item-section>{{ item.label }}</q-item-section>
            </q-item>
        </q-list>
    </q-drawer>
</template>

<script setup lang="ts">
const authStore = useAuthStore();
const { t } = useI18n();

type MenuItem = {
    icon: string;
    label: string;
    to: string;
    visible: boolean;
};

const userMenuItems: MenuItem[] = [
    {
        icon: 'mdi-view-dashboard',
        label: 'Dashboard',
        to: '/dashboard',
        visible: true,
    },
    {
        icon: 'mdi-file-document-multiple',
        label: 'My Manuscripts',
        to: '/my-manuscripts',
        visible: true,
    },
    {
        icon: 'mdi-newspaper-variant-multiple-outline',
        label: 'My Publications',
        to: '/my-publications',
        visible: true,
    },
    {
        icon: 'mdi-file-sign',
        label: 'My Reviews',
        to: '/my-reviews',
        visible: true,
    },
];

const exploreMenuItems: MenuItem[] = [
    {
        icon: 'mdi-newspaper-variant-multiple-outline',
        label: 'Publications',
        to: '/publications',
        visible: true,
    },
    {
        icon: 'mdi-account-group-outline',
        label: 'Authors',
        to: '/authors',
        visible: true,
    },
    {
        icon: 'mdi-archive-star-outline',
        label: 'Sensitive Issues',
        to: '/sensitive-issues',
        visible: true,
    },
];
</script>
