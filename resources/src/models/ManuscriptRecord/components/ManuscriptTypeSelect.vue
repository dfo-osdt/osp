<script setup lang="ts">
const props = defineProps<{
  modelValue: string
}>()

const { t } = useI18n()

const options = ref([
  {
    label: t('common.primary-manuscript'),
    value: 'primary',
  },
  {
    label: t('common.secondary-manuscript'),
    value: 'secondary',
  },
  {
    label: t('common.preprint-manuscript'),
    value: 'preprint',
  },
])

const value = useVModel(props, 'modelValue')
</script>

<template>
  <q-select
    v-model="value"
    :options="options"
    label="$t('common.manuscript-type')"
    emit-value
    map-options
  />
</template>

<style scoped></style>
