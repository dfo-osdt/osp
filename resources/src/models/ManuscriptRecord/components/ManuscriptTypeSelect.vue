<script setup lang="ts">
const value = defineModel<string>()

const { t } = useI18n()

const options = computed(() => [
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
