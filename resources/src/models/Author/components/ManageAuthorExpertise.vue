<template>
    <ContentCard secondary class="q-mt-lg">
        <template #title>
            <q-icon
                name="mdi-book-education-outline"
                size="md"
                class="q-pr-sm"
            />
            {{ $t('manage-author-profile-view.title') }}</template
        >
        <p>A description of why this matters..</p>
        <ExpertiseSelect />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import ExpertiseSelect from '@/models/Expertise/components/ExpertiseSelect.vue';
</script>

<style scoped></style>
