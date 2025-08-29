<script setup lang="ts">
const value = defineModel<string | null>()

const { t } = useI18n()

const options = computed(() => [
  {
    label: t('manuscript.primary'),
    value: 'primary',
  },
  {
    label: t('manuscript.secondary'),
    value: 'secondary',
  },
  {
    label: t('manuscript.preprint'),
    value: 'preprint',
  },
])
</script>

<template>
  <q-select
    v-model="value"
    :options="options"
    :label="$t('common.manuscript-type')"
    outlined
    emit-value
    map-options
  />
</template>

<style scoped></style>
