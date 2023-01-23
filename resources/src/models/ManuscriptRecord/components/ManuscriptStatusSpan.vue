<template>
    <span>
        {{ statusLabel }}
    </span>
</template>

<script setup lang="ts">
import { ManuscriptRecordStatus } from '../ManuscriptRecord';

const { t } = useI18n();

const props = defineProps<{
    status: ManuscriptRecordStatus;
}>();

const statusLabel = computed(() => {
    switch (props.status) {
        case 'draft':
            return t('manuscript.draft');
        case 'in_review':
            return t('manuscript.in_review');
        case 'reviewed':
            return t('manuscript.reviewed');
        case 'submitted':
            return t('manuscript.submitted');
        case 'accepted':
            return t('manuscript.accepted');
        case 'withdrawn':
            return t('manuscript.withdrawn');
        case 'withheld':
            return t('manuscript.withheld');
    }
});
</script>

<style scoped></style>
