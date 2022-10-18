<template>
    <span>{{ value }}</span>
</template>

<script setup lang="ts">
import { ManagementReviewStepStatus } from '../ManagementReviewStep';
const { t } = useI18n();

const props = defineProps<{
    status: ManagementReviewStepStatus;
}>();

const value = computed(() => {
    switch (props.status) {
        case 'pending':
            return t('common.pending');
        case 'deferred':
            return t('common.deferred');
        case 'completed':
            return t('common.completed');
        default:
            return 'Unknown';
    }
});
</script>

<style scoped></style>
