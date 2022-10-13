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
import { useQuasar } from 'quasar';

const authStore = useAuthStore();
const leftDrawerOpen = useStorage('leftDrawerOpen', true);

function toggleLeftDrawer() {
    leftDrawerOpen.value = !leftDrawerOpen.value;
}

// idle timer and auto logout
const $q = useQuasar();
const { idle, lastActive } = useIdle(authStore.idleTimerMin * 60 * 1000, {
    listenForVisibilityChange: false,
}); // duration set in .env file as VITE_IDLE_TIMER_MIN

// check if user is idle, notify user, and logout if no activity
watch(
    () => idle.value,
    (isIdle) => {
        if (isIdle) {
            // do nothing if user is not logged in?
            if (!authStore.isAuthenticated) return;

            const inactiveMinutes = Math.round(
                (Date.now() - lastActive.value) / 1000 / 60
            );

            const logoutTimeout = setTimeout(() => {
                if (idle.value) authStore.logout();
            }, 2 * 60 * 1000);

            $q.notify({
                message: `You have been inactive for ${inactiveMinutes} minutes. You will be logged out in 2 minutes.`,
                color: 'warning',
                timeout: 120000,
                progress: true,
                actions: [
                    {
                        label: 'Stay logged in',
                        color: 'white',
                        handler: () => {
                            clearTimeout(logoutTimeout);
                        },
                    },
                ],
            });
        }
    }
);

</script>
