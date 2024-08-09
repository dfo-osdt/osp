<script setup lang="ts">
import { QInput } from 'quasar'

const { t } = useI18n()

const [modelValue, modelModifiers] = defineModel<string, 'stripBaseUrl'>({
  get(val) {
    if (modelModifiers.stripBaseUrl) {
      // return only the orcid number and save base url fo
      // set method
      return val.split('/').pop() || ''
    }
    return val
  },
  set(val) {
    if (modelModifiers.stripBaseUrl) {
      // add base url to orcid number
      if (val.length === 0) {
        return ''
      }
      return `https://orcid.org/${val}`
    }
    return val
  },
})

const rules = [
  (val: string) =>
    val.length === 0
    || /^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/.test(val)
    || t('common.validation.orcid-invalid'),
  // check orcid checksum
  (val: string) => {
    if (val.length < 19) {
      return true
    }
    // checksum is the last digit
    const givenCheckSum = val[18]
    const digits = val
      .substring(0, 18)
      .replaceAll('-', '')
      .split('')
      .map(x => Number.parseInt(x))
    const total = digits.reduce((acc, x) => {
      return (acc + x) * 2
    }, 0)
    const remainder = total % 11
    const result = (12 - remainder) % 11
    const checksum = result == 10 ? 'X' : result.toString()
    return (
      givenCheckSum === checksum
      || t('common.validation.orcid-is-invalid-checksum')
    )
  },
]
</script>

<template>
  <QInput
    ref="input"
    v-model="modelValue"
    outlined
    label="ORCID"
    mask="####-####-####-###X"
    prefix="https://orcid.org/"
    :rules="rules"
  />
</template>

<style scoped></style>
