<script setup lang="ts">
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'
import { QInput } from 'quasar'

const props = defineProps<{
  modelValue: string | null
  required?: boolean
}>()

const emit = defineEmits(['update:modelValue'])

const { t } = useI18n()

const doi = useVModel(props, 'modelValue', emit)

const doiInput = ref<QInput | null>(null)
const isReadOnly = computed(() => doiInput.value?.$props.readonly ?? false)

const rules = [
  // required
  (val: string | null) => {
    if (!props.required)
      return true
    const msg = t('common.required')
    if (val === null)
      return msg
    return val.length > 0 || msg
  },
  (val: string | null) => {
    if (val === '')
      return true
    if (val === null)
      return true
    const doiRegex = /^https:\/\/doi.org\/10\.\d{4,9}\/[-.\w;()/:]+$/
    return doiRegex.test(val) || t('common.validation.invalid-doi')
  },
]

const valid = computed(() => rules.every(rule => rule(doi.value) === true))
</script>

<template>
  <QInput
    ref="doiInput"
    v-model="doi"
    :label="$t('common.doi')"
    outlined
    :rules="rules"
    hint="Format: https://doi.org/10.1038/xyz123"
    :hide-hint="isReadOnly"
  >
    <template v-if="valid && doi" #after>
      <CopyToClipboardButton
        :text="doi"
      />
    </template>
  </QInput>
</template>

<style scoped></style>
