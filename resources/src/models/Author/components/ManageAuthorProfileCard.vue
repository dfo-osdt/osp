<template>
    <ContentCard>
        <template #title>Manage your Author Profile</template>
        Manage Author profile..
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';

const props = defineProps<{
    author_id: number;
}>();

async function getAuthor() {}
</script>

<style scoped></style>
