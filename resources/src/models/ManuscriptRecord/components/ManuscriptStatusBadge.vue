<script setup lang="ts">
import type { ManuscriptRecordStatus } from '../ManuscriptRecord'
import ManuscriptStatusSpan from './ManuscriptStatusSpan.vue'

const props = defineProps<{
  status: ManuscriptRecordStatus
}>()

// computed color of badge based on status
const color = computed(() => {
  switch (props.status) {
    case 'draft':
      return 'blue'
    case 'in_review':
      return 'orange'
    case 'reviewed':
      return 'orange'
    case 'submitted':
      return 'orange'
    case 'accepted':
      return 'green'
    case 'withdrawn':
      return 'red'
    default:
      return 'red'
  }
})
</script>

<template>
  <q-badge outline :color="color">
    <ManuscriptStatusSpan :status="status" />
  </q-badge>
</template>

<style scoped></style>
