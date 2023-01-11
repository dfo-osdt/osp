<template>
    <ChangePasswordCard />
    <ContentCard secondary class="q-mt-lg">
        <template #title>Your Login History</template>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import ChangePasswordCard from '../components/ChangePasswordCard.vue';
</script>

<style scoped></style>
