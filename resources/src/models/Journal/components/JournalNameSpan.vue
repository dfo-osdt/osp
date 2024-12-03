<script setup lang="ts">
import type { JournalResource } from '../Journal'

const props = defineProps<{
  journal: JournalResource | undefined
}>()

const journalName = computed(() => {
  if (!props.journal) {
    return '-'
  }
  const { title } = props.journal.data
  return title
})
</script>

<template>
  <span>{{ journalName }}</span>
</template>

<style scoped></style>
