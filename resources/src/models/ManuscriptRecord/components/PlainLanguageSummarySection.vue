<script setup lang="ts">
import type { ManuscriptRecord } from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import { UtilityService } from '@/api/utils'
import LocaleSelect from '@/components/LocaleSelect.vue'
import QuestionEditor from '@/components/QuestionEditor.vue'
import RequiredSpan from '@/components/RequiredSpan.vue'

const props = defineProps<{
  loading?: boolean
  readonly?: boolean
}>()

const manuscriptData = defineModel<ManuscriptRecord>({ required: true })

const { t } = useI18n()
const $q = useQuasar()

// PLS generation and translation state
const PLSLoading = ref(false)
const translateLoading = ref(false)

const enablePLSPrompt = computed(() => {
  if (!manuscriptData.value) {
    return false
  }

  const locale = manuscriptData.value.pls_source_language

  return (
    !props.readonly
    && (manuscriptData.value[`pls_${locale}`] === '')
    && manuscriptData.value.abstract.length > 250
    && !PLSLoading.value
  )
})

const enableTranslatePLS = computed(() => {
  if (!manuscriptData.value) {
    return false
  }

  const sourceLanguage = manuscriptData.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'

  return (
    !props.readonly
    && (manuscriptData.value[`pls_${sourceLanguage}`] !== '')
    && (manuscriptData.value[`pls_${targetLanguage}`] === '')
    && !translateLoading.value
    && !PLSLoading.value
  )
})

const translateDisabledTooltip = computed(() => {
  if (!manuscriptData.value) {
    return ''
  }

  const sourceLanguage = manuscriptData.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'

  if (manuscriptData.value[`pls_${sourceLanguage}`] === '') {
    return t('mrf.source-pls-required-for-translation')
  }

  if (manuscriptData.value[`pls_${targetLanguage}`] !== '') {
    return t('mrf.target-pls-already-exists-erase-to-translate')
  }

  return ''
})

const plsDisabledTooltip = computed(() => {
  if (!manuscriptData.value) {
    return ''
  }

  const locale = manuscriptData.value.pls_source_language

  if (manuscriptData.value[`pls_${locale}`] !== '') {
    return t('mrf.pls-already-generated-erase-it-to-generate-a-new-one')
  }

  if (manuscriptData.value.abstract.length < 250) {
    return t('mrf.abstract-must-be-at-least-250-characters')
  }

  return ''
})

async function generatePLS() {
  PLSLoading.value = true
  if (!manuscriptData.value?.abstract)
    return
  if (manuscriptData.value?.abstract === '')
    return

  // which pls are we generating - this is based on
  const locale = manuscriptData.value?.pls_source_language

  manuscriptData.value[`pls_${locale}`] = t(
    'mrf.generating-pls-please-be-patient',
  )

  await UtilityService.generatePls(manuscriptData.value.abstract, locale)
    .then((response) => {
      if (!manuscriptData.value)
        return

      manuscriptData.value[`pls_${locale}`] = response.data.pls

      $q.notify({
        type: 'positive',
        color: 'primary',
        message: t('mrf.pls-generated-successfully'),
      })
    })
    .catch((error) => {
      console.error(error)
    })
    .finally(() => {
      PLSLoading.value = false
    })
}

async function translatePLS() {
  translateLoading.value = true
  if (!manuscriptData.value)
    return

  const sourceLanguage = manuscriptData.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'
  const sourcePLS = manuscriptData.value[`pls_${sourceLanguage}`]

  if (!sourcePLS || sourcePLS === '')
    return

  manuscriptData.value[`pls_${targetLanguage}`] = t(
    'mrf.translating-pls-please-be-patient',
  )

  await UtilityService.translatePls(sourcePLS, targetLanguage)
    .then((response) => {
      if (!manuscriptData.value)
        return

      manuscriptData.value[`pls_${targetLanguage}`] = response.data.pls

      $q.notify({
        type: 'positive',
        color: 'primary',
        message: t('mrf.pls-translated-successfully'),
      })
    })
    .catch((error) => {
      console.error(error)
    })
    .finally(() => {
      translateLoading.value = false
    })
}
</script>

<template>
  <div class="q-mb-lg">
    <div class="q-ml-xs">
      <div class="text-body1 text-primary text-weight-medium">
        {{ $t('common.plain-language-summary') }}
      </div>
      <div class="q-my-xs">
        <p>
          {{ $t('mrf.pls-text') }}
        </p>
      </div>
    </div>
    <div>
      <div class="text-body2 text-primary">
        Source Language for the PLS<RequiredSpan />
      </div>
      <p>
        Please select the official language you will use for your plain language summary. You may provide both English and French summaries, but only the selected language is required to submit your manuscript.
      </p>
      <LocaleSelect v-model="manuscriptData.pls_source_language" :label="t('mrf.pls-locale')" />
    </div>
    <div class="row items-center justify-between q-mt-lg">
      <div class="col text-body2 text-grey-8">
        <q-icon name="mdi-information-outline" class="q-mr-xs" />
        {{ $t('mrf.pls-help-ai-text') }}
      </div>
      <div class="col-auto row">
        <q-btn
          color="primary"
          :label="$t('mrf.generate-pls')"
          icon="mdi-brain"
          outline
          rounded
          :disable="!enablePLSPrompt"
          :loading="PLSLoading"
          class="q-ml-sm"
          @click="generatePLS"
        >
          <q-tooltip v-if="!enablePLSPrompt" class="text-body2">
            {{ plsDisabledTooltip }}
          </q-tooltip>
        </q-btn>
        <q-btn
          color="secondary"
          :label="$t('mrf.translate-pls')"
          icon="mdi-translate"
          outline
          rounded
          :disable="!enableTranslatePLS"
          :loading="translateLoading"
          class="q-ml-sm"
          @click="translatePLS"
        >
          <q-tooltip v-if="!enableTranslatePLS" class="text-body2">
            {{ translateDisabledTooltip }}
          </q-tooltip>
        </q-btn>
      </div>
    </div>
  </div>
  <!-- Display the source language PLS editor first -->
  <template v-if="manuscriptData.pls_source_language === 'en'">
    <QuestionEditor
      v-model="manuscriptData.pls_en"
      :title="$t('common.plain-language-summary-en')"
      :disable="loading || PLSLoading"
      :readonly="readonly"
      :required="true"
      class="q-mb-md"
    />
    <QuestionEditor
      v-model="manuscriptData.pls_fr"
      :title="$t('common.plain-language-summary-fr')"
      :disable="loading || PLSLoading"
      :readonly="readonly"
      :required="false"
      class="q-mb-md"
    />
  </template>
  <template v-else>
    <QuestionEditor
      v-model="manuscriptData.pls_fr"
      :title="$t('common.plain-language-summary-fr')"
      :disable="loading || PLSLoading"
      :readonly="readonly"
      :required="true"
      class="q-mb-md"
    />
    <QuestionEditor
      v-model="manuscriptData.pls_en"
      :title="$t('common.plain-language-summary-en')"
      :disable="loading || PLSLoading"
      :readonly="readonly"
      :required="false"
      class="q-mb-md"
    />
  </template>
</template>
