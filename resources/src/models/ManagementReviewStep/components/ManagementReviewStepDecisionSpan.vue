<script setup lang="ts">
import type { ManagementReviewStepDecision } from '../ManagementReviewStep'

const props = defineProps<{
  decision: ManagementReviewStepDecision
  showLabel?: boolean
}>()

const { t } = useI18n()

const decision = computed(() => {
  switch (props.decision) {
    case 'complete':
      return t('common.complete')
    case 'none':
      return t('common.none')
    case 'revision':
      return t('common.flagged')
    case 'withdrawn':
      return t('common.withdrawn')
    default:
      return t('common.unknown')
  }
})

const value = computed(() => {
  if (props.showLabel)
    return `${t('common.review')}: ${decision.value}`
  return decision.value
})
</script>

<template>
  <span>{{ value }}</span>
</template>

<style scoped></style>
