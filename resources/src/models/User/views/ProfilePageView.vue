<template>
    <MainPageLayout title="My Profile">
        <template v-if="authStore.user && !authStore.loading">
            <ManageUserProfileCard
                class="q-ma-md"
                :user-id="authStore.user.id"
                @saved="authStore.refreshUser(true)"
            />
            <ManageAuthorProfileCard
                class="q-ma-md"
                :author-id="authStore.user.author.data.id"
            />
        </template>
        <q-skeleton v-else class="q-ma-md" height="200px" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManageUserProfileCard from '../components/ManageUserProfileCard.vue';
import ManageAuthorProfileCard from '@/models/Author/components/ManageAuthorProfileCard.vue';

const authStore = useAuthStore();
</script>

<style scoped></style>
