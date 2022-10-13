<template>
    <q-header bordered class="q-py-sm bg-white text-accent">
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
            <q-toolbar-title class="q-mt-sm text-black">{{
                $t('common.app-name')
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
                @click="$router.push({ name: 'login' })"
            ></q-btn>
            <!-- drop down menu with logout, profile, and dashboard -->
            <q-btn
                v-if="authStore.isAuthenticated"
                round
                color="accent"
                :label="authStore.user?.initials"
            >
                <q-menu :offset="[0, 10]">
                    <q-list>
                        <q-item-label header>{{
                            authStore.user?.fullName
                        }}</q-item-label>
                        <q-separator></q-separator>
                        <q-item v-ripple to="/settings" clickable>
                            <q-item-section>
                                <q-item-label>Settings</q-item-label>
                            </q-item-section>
                            <q-item-section avatar>
                                <q-icon name="mdi-account-cog-outline" />
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

// stores
const authStore = useAuthStore();
const localeStore = useLocaleStore();

// toggle the left drawer
const emit = defineEmits(['toggleLeftDrawer']);
function toggleLeftDrawer() {
    emit('toggleLeftDrawer');
}

</script>

<style scoped></style>
