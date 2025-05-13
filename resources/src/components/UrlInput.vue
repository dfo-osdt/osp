<script setup lang="ts">
import type { QInputProps, ValidationRule } from 'quasar'

type UrlInputProps = Omit<QInputProps, 'modelValue' | 'lablel'> & {
  label?: string
  required?: boolean
}

const props = defineProps<UrlInputProps>()
const attrs = useAttrs()

const url = defineModel<string>({
  default: '',
  required: true,
})

const { t } = useI18n()

const computedRules = computed<ValidationRule[]>(() => props.rules ?? [
  // required
  (val: string) => {
    if (!props.required)
      return true
    const msg = t('common.required')
    if (val.length === 0)
      return msg
    return true
  },
  (val: string) =>
    val.length === 0
    || (/^https?:\/\/(?:www\.)?[-\w@:%.+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b[-\w()!@:%+.~#?&/=]*$/.test(val))
    || t('common.validation.url-invalid'),
])
</script>

<template>
  <q-input
    v-model="url"
    v-bind="attrs"
    :label="props.label ?? t('common.url')"
    outlined
    class="q-mx-sm"
    :rules="computedRules"
  />
</template>
