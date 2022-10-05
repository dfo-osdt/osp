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


async function getAuthor() {
    const response = await axios.get('/api/author/profile');
    return response.data;
}

</script>

<style scoped>

</style>