<template>
    <q-header bordered class="q-py-sm bg-white text-primary">
        <q-toolbar>
            <q-btn
                v-if="authStore.isAuthenticated"
                class="q-mr-lg q-mt-xs"
                flat
                dense
                round
                icon="menu"
                aria-label="Menu"
                @click="toggleLeftDrawer"
            />
            <router-link to="/">
                <q-img src="/assets/logo.svg" width="40px"></q-img>
            </router-link>
            <q-toolbar-title class="q-mt-sm">{{
                t('common.app-name')
            }}</q-toolbar-title>
            <!-- login button -->
            <q-btn
                v-if="!authStore.isAuthenticated"
                outline
                dense
                icon="mdi-login"
                label="Login"
                aria-label="Login"
                padding="xs md"
                @click="$router.push('/login')"
            ></q-btn>
            <!-- drop down menu with logout, profile, and dashboard -->
            <q-btn
                v-if="authStore.isAuthenticated"
                round
                color="accent"
                label="VA"
            >
                <q-menu>
                    <q-list>
                        <q-item v-ripple to="/profile" clickable>
                            <q-item-section>
                                <q-item-label>Profile</q-item-label>
                            </q-item-section>
                            <q-item-section avatar>
                                <q-icon name="mdi-account-details-outline" />
                            </q-item-section>
                        </q-item>
                        <q-item v-ripple to="/dashboard" clickable>
                            <q-item-section>
                                <q-item-label>Dashboard</q-item-label>
                            </q-item-section>
                            <q-item-section avatar>
                                <q-icon name="mdi-view-dashboard-outline" />
                            </q-item-section>
                        </q-item>
                        <q-item v-ripple clickable @click="authStore.logout">
                            <q-item-section>
                                <q-item-label>Logout</q-item-label>
                            </q-item-section>
                            <q-item-section avatar>
                                <q-icon name="mdi-logout" />
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-menu>
            </q-btn>
            <q-btn
                :label="localeStore.otherLocale"
                flat
                round
                class="q-mx-xs"
                @click="localeStore.toggleLocale()"
            ></q-btn>
        </q-toolbar>
    </q-header>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';

const emit = defineEmits(['toggleLeftDrawer']);
const authStore = useAuthStore();
const { t } = useI18n();
const $q = useQuasar();
const localeStore = useLocaleStore();

function toggleLeftDrawer() {
    emit('toggleLeftDrawer');
}
</script>

<style scoped></style>
