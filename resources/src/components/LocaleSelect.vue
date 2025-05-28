<script setup lang="ts">
import type { Locale } from '@/stores/LocaleStore'

const props = defineProps<{
  modelValue: Locale
  label?: string
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', value: Locale): void
}>()

const { t } = useI18n()

const locale = useVModel(props, 'modelValue', emit)

const label = computed(() => props.label || t('LocaleSelect.label'))

const options = computed(() => [
  { label: t('common.english'), value: 'en' },
  { label: t('common.french'), value: 'fr' },
])
</script>

<template>
  <q-select
    v-model="locale"
    emit-value
    map-options
    :label="label"
    outlined
    :options="options"
  />
</template>

<style scoped></style>
