<template>
    <span>
        {{ statusLabel }}
    </span>
</template>

<script setup lang="ts">
import { ManuscriptRecordStatus } from '../ManuscriptRecord';

const t = useI18n();

const props = defineProps<{
    status: ManuscriptRecordStatus;
}>();

const statusLabel = computed(() => {
    switch (props.status) {
        case 'draft':
            return t.t('manuscript.draft');
        case 'in_review':
            return t.t('manuscript.in_review');
        case 'reviewed':
            return t.t('manuscript.reviewed');
        case 'submitted':
            return t.t('manuscript.submitted');
        case 'accepted':
            return t.t('manuscript.accepted');
        case 'withdrawn':
            return t.t('manuscript.withdrawn');
        case 'withheld':
            return t.t('manuscript.withheld');
    }
});
</script>

<style scoped></style>
