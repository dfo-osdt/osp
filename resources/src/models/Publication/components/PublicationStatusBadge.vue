<script setup lang="ts">
import type { PublicationStatus } from '../Publication'
import PublicationStatusSpan from './PublicationStatusSpan.vue'

defineProps<{
  status: PublicationStatus
}>()
</script>

<template>
  <q-badge outline color="blue">
    <PublicationStatusSpan :status="status" />
  </q-badge>
</template>

<style scoped></style>
