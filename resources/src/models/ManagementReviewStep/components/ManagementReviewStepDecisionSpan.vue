<script setup lang="ts">
import type { ManagementReviewStepDecision } from '../ManagementReviewStep'

const props = defineProps<{
  decision: ManagementReviewStepDecision
  showLabel?: boolean
}>()

const { t } = useI18n()

const value = computed(() => {
  if (props.showLabel)
    return `${t('common.decision')}: ${decision.value}`
  return decision.value
})

const decision = computed(() => {
  switch (props.decision) {
    case 'approved':
      return t('common.approved')
    case 'none':
      return t('common.none')
    case 'withheld':
      return t('common.withheld')
    case 'flagged':
      return t('common.flagged')
    case 'withdrawn':
      return t('common.withdrawn')
    default:
      return t('common.unknown')
  }
})
</script>

<template>
  <span>{{ value }}</span>
</template>

<style scoped></style>
