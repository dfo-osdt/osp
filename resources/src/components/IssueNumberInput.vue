<script setup lang="ts">
import type { QInputProps, ValidationRule } from 'quasar'
import { QInput } from 'quasar'

type IssueNumberInputProps = Omit<QInputProps, 'modelValue' | 'label'> & {
  required?: boolean
}

const props = defineProps<IssueNumberInputProps>()
const attrs = useAttrs()

const { t } = useI18n()

const modelValue = defineModel<string | null>({ default: '' })

const computedRules = computed<ValidationRule[]>(() => props.rules ?? [
  (val: string | null) => {
    if (!props.required)
      return true
    if (val === null || val.length === 0)
      return t('common.required')
    return true
  },
])
</script>

<template>
  <QInput
    v-model="modelValue"
    v-bind="attrs"
    outlined
    :label="t('common.issue-number')"
    :hint="t('common.issue-number-hint')"
    :rules="computedRules"
  />
</template>
