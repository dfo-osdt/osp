<script setup lang="ts">
import type { QInputProps, ValidationRule } from 'quasar'
import { QInput } from 'quasar'

type IsbnInputProps = Omit<QInputProps, 'modelValue' | 'label'> & {
  required?: boolean
}

const props = defineProps<IsbnInputProps>()
const attrs = useAttrs()

const { t } = useI18n()

const modelValue = defineModel<string | null>({ default: '' })

const computedRules = computed<ValidationRule[]>(() => props.rules ?? [
  (val: string) => {
    if (!props.required)
      return true
    if (val.length === 0)
      return t('common.required')
    return true
  },
  (val: string) => {
    if (val.length === 0)
      return true
    if (!/^\d{13}$/.test(val))
      return t('common.validation.isbn-invalid')
    // Validate ISBN-13 checksum
    let sum = 0
    for (let i = 0; i < 12; i++) {
      sum += Number(val[i]) * (i % 2 === 0 ? 1 : 3)
    }
    const checkDigit = (10 - (sum % 10)) % 10
    if (Number(val[12]) !== checkDigit)
      return t('common.validation.isbn-invalid')
    return true
  },
])

function onPaste(event: ClipboardEvent) {
  const pastedText = event.clipboardData?.getData('text') ?? ''
  const digitsOnly = pastedText.replace(/\D/g, '')
  if (digitsOnly !== pastedText) {
    event.preventDefault()
    modelValue.value = digitsOnly
  }
}
</script>

<template>
  <QInput
    v-model="modelValue"
    v-bind="attrs"
    outlined
    label="ISBN"
    :hint="t('common.isbn-hint')"
    :rules="computedRules"
    mask="#############"
    @paste="onPaste"
  />
</template>

<style scoped></style>
