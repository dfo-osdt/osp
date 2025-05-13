<script setup lang="ts">
import type { ManuscriptRecordStatus } from '../ManuscriptRecord'

const props = defineProps<{
  status: ManuscriptRecordStatus
}>()

const { t } = useI18n()

const statusLabel = computed(() => {
  switch (props.status) {
    case 'draft':
      return t('manuscript.draft')
    case 'in_review':
      return t('manuscript.in_review')
    case 'reviewed':
      return t('manuscript.reviewed')
    case 'submitted':
      return t('manuscript.submitted')
    case 'accepted':
      return t('manuscript.accepted')
    case 'withdrawn':
      return t('manuscript.withdrawn')
    default:
      return 'N/A'
  }
})
</script>

<template>
  <span>
    {{ statusLabel }}
  </span>
</template>

<style scoped></style>
