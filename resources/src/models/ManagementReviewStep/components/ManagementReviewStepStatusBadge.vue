<script setup lang="ts">
import type { ManagementReviewStepStatus } from '../ManagementReviewStep'
import ManagementReviewStepStatusSpan from './ManagementReviewStepStatusSpan.vue'

const props = defineProps<{
  status: ManagementReviewStepStatus
}>()

const color = computed(() => {
  switch (props.status) {
    case 'pending':
      return 'orange-5'
    case 'completed':
      return 'positive'
    case 'on_hold':
      return 'orange-5'
    default:
      return 'orange-5'
  }
})
</script>

<template>
  <q-badge :color="color">
    <ManagementReviewStepStatusSpan :status="props.status" show-label />
  </q-badge>
</template>
