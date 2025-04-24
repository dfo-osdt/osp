<script setup lang="ts">
import type { ManuscriptRecordType } from '../ManuscriptRecord'

const props = defineProps<{
  type: ManuscriptRecordType
}>()

const { t } = useI18n()

const typeLabel = computed(() => {
  switch (props.type) {
    case 'primary':
      return t('manuscript.primary')
    case 'secondary':
      return t('manuscript.secondary')
    case 'preprint':
      return t('manuscript.preprint')
    default:
      return 'N/A'
  }
})
</script>

<template>
  <q-badge outline color="primary">
    {{ typeLabel }}
  </q-badge>
</template>

<style scoped></style>
