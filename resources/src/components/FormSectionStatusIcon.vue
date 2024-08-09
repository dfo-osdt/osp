<script setup lang="ts">
export type FormSectionStatus = 'complete' | 'incomplete' | 'error'

const props = defineProps<{
  status: FormSectionStatus
}>()

const { t } = useI18n()

const icon = computed(() => {
  switch (props.status) {
    case 'complete':
      return 'mdi-checkbox-marked-circle-outline'
    case 'incomplete':
      return 'mdi-checkbox-blank-circle-outline'
    case 'error':
      return 'mdi-alert-circle-outline'
    default:
      return 'mdi-checkbox-blank-circle-outline'
  }
})

const color = computed(() => {
  switch (props.status) {
    case 'complete':
      return 'primary'
    case 'incomplete':
      return 'primary'
    case 'error':
      return 'red'
    default:
      return 'primary'
  }
})

const tooltip = computed(() => {
  switch (props.status) {
    case 'complete':
      return t('form-section-status.section-complete')
    case 'incomplete':
      return t('form-section-status.section-incomplete')
    case 'error':
      return t('form-section-status.section-error')
    default:
      return ''
  }
})
</script>

<template>
  <q-icon :name="icon" :color="color" size="sm">
    <q-tooltip>{{ tooltip }}</q-tooltip>
  </q-icon>
</template>

<style scoped></style>
