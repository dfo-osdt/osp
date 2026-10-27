<script setup lang="ts">
import { QInput } from 'quasar'

const ORCID_REGEX = /^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/
const PASTED_ORCID_REGEX = /(\d{4})-?(\d{4})-?(\d{4})-?(\d{3}[\dX])/i

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

// the ORCID copy button copies the full URL, which the mask would mangle,
// so pull the iD out of whatever was pasted
function onPaste(event: ClipboardEvent) {
  const match = event.clipboardData?.getData('text').match(PASTED_ORCID_REGEX)
  if (!match) {
    return
  }
  event.preventDefault()
  modelValue.value = match.slice(1).join('-').toUpperCase()
}

const rules = [
  (val: string) =>
    val.length === 0
    || ORCID_REGEX.test(val)
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
    const checksum = result === 10 ? 'X' : result.toString()
    return (
      givenCheckSum === checksum
      || t('common.validation.orcid-is-invalid-checksum')
    )
  },
]
</script>

<template>
  <QInput
    v-model="modelValue"
    outlined
    label="ORCID"
    data-test="orcid-input"
    mask="####-####-####-###X"
    prefix="https://orcid.org/"
    :rules="rules"
    @paste="onPaste"
  />
</template>

<style scoped></style>
