<template>
    <ContentCard secondary>
        <template #title>Authentication History</template>
        <template #subtitle
            >Suspicious activity? Change your password and report it to
            us.</template
        >
        <pre>{{ authStore.userAuthentications }}</pre>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';

const authStore = useAuthStore();
authStore.getAuthentications();
</script>

<style scoped></style>
