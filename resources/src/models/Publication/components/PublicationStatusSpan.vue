<script setup lang="ts">
import type { PublicationStatus } from '../Publication'

const props = defineProps<{
  status: PublicationStatus
}>()

const t = useI18n()

const statusLabel = computed(() => {
  switch (props.status) {
    case 'accepted':
      return t.t('publication.accepted')
    case 'published':
      return t.t('publication.published')
    default:
      return ''
  }
})
</script>

<template>
  <span>
    {{ statusLabel }}
  </span>
</template>

<style scoped></style>
