<template>
    <q-layout view="hHh Lpr lFf">
        <MainHeader @toggle-left-drawer="toggleLeftDrawer"></MainHeader>
        <MainDrawer
            v-if="authStore.isAuthenticated"
            v-model="authStore.leftDrawerOpen"
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

const { t } = useI18n();
const router = useRouter();
const authStore = useAuthStore();

function toggleLeftDrawer() {
    authStore.leftDrawerOpen = !authStore.leftDrawerOpen;
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
                if (idle.value)
                    router.push({ name: 'logout', query: { inactive: '1' } });
            }, 2 * 60 * 1000);

            $q.notify({
                message: t('common.inactive-msg', [inactiveMinutes]),
                color: 'warning',
                timeout: 120000,
                progress: true,
                actions: [
                    {
                        label: t('common.stay-logged-in'),
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
