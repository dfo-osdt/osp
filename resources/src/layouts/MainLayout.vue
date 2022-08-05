<template>
    <q-layout view="lHh Lpr lFf">
        <MainHeader @toggle-left-drawer="toggleLeftDrawer"></MainHeader>
        <MainDrawer
            v-if="authStore.isAuthenticated"
            v-model="leftDrawerOpen"
            show-if-above
            bordered
        />
        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script setup lang="ts">
import MainHeader from '@/components/MainHeader.vue';
import MainDrawer from '@/components/MainDrawer.vue';

const authStore = useAuthStore();
const leftDrawerOpen = ref(true);

function toggleLeftDrawer() {
    leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>
