<template>
    <ManageUserProfileCard
        v-if="authStore.user && !authStore.loading"
        :user-id="authStore.user.id"
        @saved="authStore.refreshUser(true)"
    />
</template>

<script setup lang="ts">
import ManageUserProfileCard from '../components/ManageUserProfileCard.vue';

const authStore = useAuthStore();
</script>

<style scoped></style>
