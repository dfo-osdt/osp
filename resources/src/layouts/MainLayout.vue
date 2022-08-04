<template>
    <q-layout view="hHh Lpr lFf">
        <MainHeader @toggle-left-drawer="toggleLeftDrawer"></MainHeader>

        <q-drawer
            v-if="authStore.isAuthenticated"
            v-model="leftDrawerOpen"
            show-if-above
            bordered
        >
            <q-list>
                <q-item-label header> Essential Links </q-item-label>
            </q-list>
        </q-drawer>
        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script setup lang="ts">
import MainHeader from '@/components/MainHeader.vue';

const authStore = useAuthStore();
const leftDrawerOpen = ref(true);

function toggleLeftDrawer() {
    leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>
