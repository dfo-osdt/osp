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
    return `${t('common.status')}: ${status.value}`;
});

const status = computed(() => {
    switch (props.status) {
        case 'pending':
            return t('common.pending');
        case 'deferred':
            return t('common.deferred');
        case 'completed':
            return t('common.completed');
        case 'on_hold':
            return t('common.on_hold');
        default:
            return t('common.unknown');
    }
});
</script>

<style scoped></style>
