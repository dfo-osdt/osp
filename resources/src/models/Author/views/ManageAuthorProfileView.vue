<template>
    <ManageAuthorProfileCard
        v-if="authStore.user && !authStore.loading"
        :author-id="authStore.user.author.data.id"
        secondary
    />
    <ContentCard secondary class="q-mt-lg">
        <template #title>{{ $t('manage-author-profile-view.title') }}</template>
    </ContentCard>
</template>

<script setup lang="ts">
import ManageAuthorProfileCard from '../components/ManageAuthorProfileCard.vue';
import ContentCard from '@/components/ContentCard.vue';
const authStore = useAuthStore();
</script>

<style scoped></style>
