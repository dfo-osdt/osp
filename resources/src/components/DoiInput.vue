<script setup lang="ts">
import type { QInputProps, ValidationRule } from 'quasar'
import { QInput } from 'quasar'
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'

type DoiInputProps = Omit<QInputProps, 'modelValue' | 'label'> & {
  required?: boolean
}

const props = defineProps<DoiInputProps>()
const attrs = useAttrs()

const { t } = useI18n()

const modelValue = defineModel<string | null>({ default: '' })

const doiInput = ref<QInput | null>(null)
const isReadOnly = computed(() => doiInput.value?.$props.readonly ?? false)

// Match either full URL or just the DOI portion (10.xxxx/...)
const doiRegex = /^(https:\/\/doi\.org\/)?10\.\d{4,9}\/[-.\\w;()/:]+$/

function normalizeDoi(val: string | null): string | null {
  if (!val)
    return val
  const trimmed = val.trim()
  if (trimmed.length === 0)
    return trimmed
  // If it starts with 10. but not the full URL, prefix it
  if (trimmed.startsWith('10.') && !trimmed.startsWith('https://'))
    return `https://doi.org/${trimmed}`
  return trimmed
}

function onBlur() {
  modelValue.value = normalizeDoi(modelValue.value)
}

function onPaste(event: ClipboardEvent) {
  const pastedText = event.clipboardData?.getData('text') ?? ''
  const normalized = normalizeDoi(pastedText)
  if (normalized !== pastedText) {
    event.preventDefault()
    modelValue.value = normalized
  }
}

type RuleFn = (val: string | null) => boolean | string

const defaultRules: RuleFn[] = [
  (val: string | null) => {
    if (!props.required)
      return true
    if (val === null || val.length === 0)
      return t('common.required')
    return true
  },
  (val: string | null) => {
    if (val === null || val === '')
      return true
    return doiRegex.test(val) || t('common.validation.invalid-doi')
  },
]

const computedRules = computed<ValidationRule[]>(() => props.rules ?? defaultRules)

const valid = computed(() => defaultRules.every(rule => rule(modelValue.value) === true))
</script>

<template>
  <QInput
    ref="doiInput"
    v-model="modelValue"
    v-bind="attrs"
    :label="$t('common.doi')"
    outlined
    :rules="computedRules"
    hint="Format: https://doi.org/10.1038/xyz123 or 10.1038/xyz123"
    :hide-hint="isReadOnly"
    @blur="onBlur"
    @paste="onPaste"
  >
    <template v-if="valid && modelValue" #after>
      <CopyToClipboardButton
        :text="modelValue"
      />
    </template>
  </QInput>
</template>

<style scoped></style>
