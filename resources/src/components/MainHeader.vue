<template>
    <q-header elevated class="q-py-sm">
        <q-toolbar>
            <q-btn
                v-if="authStore.isAuthenticated"
                flat
                dense
                round
                icon="menu"
                aria-label="Menu"
                @click="toggleLeftDrawer"
            />

            <q-toolbar-title>Open Science Portal</q-toolbar-title>
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
        </q-toolbar>
    </q-header>
</template>

<script setup lang="ts">
const emit = defineEmits(['toggleLeftDrawer']);
const authStore = useAuthStore();

function toggleLeftDrawer() {
    emit('toggleLeftDrawer');
}
</script>

<style scoped></style>
