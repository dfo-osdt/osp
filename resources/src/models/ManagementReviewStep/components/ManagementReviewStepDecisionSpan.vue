<template>
    <span>{{ value }}</span>
</template>

<script setup lang="ts">
import { ManagementReviewStepDecision } from '../ManagementReviewStep';
const { t } = useI18n();

const props = defineProps<{
    decision: ManagementReviewStepDecision;
}>();

const value = computed(() => {
    switch (props.decision) {
        case 'approved':
            return t('common.approved');
        case 'none':
            return t('common.none');
        case 'withheld':
            return t('common.withheld');
        default:
            return t('common.unknown');
    }
});
</script>

<style scoped></style>
