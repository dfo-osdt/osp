<script setup lang="ts">
import type { JournalResource } from '../Journal'

const props = defineProps<{
  journal: JournalResource | undefined
}>()

const localeStore = useLocaleStore()

const journalName = computed(() => {
  if (!props.journal) {
    return '-'
  }
  const { title_en, title_fr } = props.journal.data
  if (localeStore.locale === 'fr') {
    return title_fr ?? title_en
  }
  return title_en
})
</script>

<template>
  <span>{{ journalName }}</span>
</template>

<style scoped></style>
