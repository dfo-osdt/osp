<script setup lang="ts">
import type { SensitivityLabel } from '@/models/Resource'

const props = defineProps<{
  sensitivity: SensitivityLabel
}>()

const { t } = useI18n()

const label = computed(() => {
  switch (props.sensitivity) {
    case 'Unclassified':
      return t('common.unclassified')
    case 'Protected A':
      return t('common.protected-a')
    default:
      return t('common.unknown')
  }
})

const tooltip = computed(() => {
  switch (props.sensitivity) {
    case 'Unclassified':
      return t('common.unclassified-tooltip')
    case 'Protected A':
      return t('common.protected-a-tooltip')
    default:
      return t('common.unknown')
  }
})
</script>

<template>
  <q-chip :label="label" color="primary" dark>
    <q-tooltip>
      {{ tooltip }}
    </q-tooltip>
  </q-chip>
</template>

<style scoped>

</style>
