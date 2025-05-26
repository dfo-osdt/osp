<script setup lang="ts">
import type { QOptionGroupProps } from 'quasar'

type Props = Omit<QOptionGroupProps, 'modelValue' | 'options'> & {
  modelValue: boolean | null
}

defineProps<Props>()

const attrs = useAttrs()

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
  <q-option-group v-model="modelValue" :options="options" inline v-bind="attrs" />
</template>
