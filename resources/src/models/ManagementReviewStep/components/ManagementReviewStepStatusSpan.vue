<script setup lang="ts">
import type { ManagementReviewStepStatus } from '../ManagementReviewStep'

const props = defineProps<{
  status: ManagementReviewStepStatus
  showLabel?: boolean
}>()

const { t } = useI18n()

const status = computed(() => {
  switch (props.status) {
    case 'pending':
      return t('common.pending')
    case 'completed':
      return t('common.completed')
    case 'on_hold':
      return t('common.on_hold')
    default:
      return t('common.unknown')
  }
})

const value = computed(() => {
  if (props.showLabel)
    return `${t('common.status')}: ${status.value}`
  return status.value
})
</script>

<template>
  <span>{{ value }}</span>
</template>

<style scoped></style>
