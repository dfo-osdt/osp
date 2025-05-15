<script setup lang="ts">
const { t } = useI18n()

const modelValue = defineModel<boolean | null>()

const options = computed(() => [
  {
    label: t('common.yes'),
    value: true,
  },
  {
    label: t('common.no'),
    value: false,
  },
])
</script>

<template>
  <q-option-group v-model="modelValue" :options="options" inline />
</template>
