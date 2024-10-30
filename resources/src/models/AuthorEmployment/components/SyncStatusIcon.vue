<script setup lang="ts">
import type { AuthorEmploymentResource } from '../AuthorEmployement'

const props = defineProps<{
  authorEmployment: AuthorEmploymentResource
}>()

const { t } = useI18n()

const color = computed(() => {
  if (props.authorEmployment.data.orcid_needs_sync)
    return 'orange'
  return 'primary'
})

const tooltip = computed(() => {
  return props.authorEmployment.data.orcid_needs_sync ? t('orcid.needs_sync') : t('orcid.synced')
})
</script>

<template>
  <q-icon :color="color" name="mdi-sync-circle" :aria-label="tooltip">
    <q-tooltip>
      {{ tooltip }}
    </q-tooltip>
  </q-icon>
</template>

<style scoped>

</style>
