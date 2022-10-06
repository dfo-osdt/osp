<template>
    <MainPageLayout
        v-if="authStore.user && !authStore.loading"
        title="My Profile"
    >
        <ManageUserProfileCard class="q-ma-md" :user-id="authStore.user.id" />
        <ManageAuthorProfileCard
            class="q-ma-md"
            :author_id="authStore.user.authorId"
        />
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManageUserProfileCard from '../components/ManageUserProfileCard.vue';
import ManageAuthorProfileCard from '@/models/Author/components/ManageAuthorProfileCard.vue';

const authStore = useAuthStore();
</script>

<style scoped></style>
