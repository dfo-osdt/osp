<template>
    <q-header elevated>
        <q-toolbar>
            <q-btn
                flat
                dense
                round
                icon="menu"
                aria-label="Menu"
                @click="toggleLeftDrawer"
            />

            <q-toolbar-title>Open Science Portal</q-toolbar-title>

            <q-button
                v-if="authStore.isAuthenticated"
                @click="authStore.logout"
                >{{ authStore.user?.name }}</q-button
            >
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
