<script setup lang="ts">
import type { ManuscriptRecord } from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import { useI18n } from 'vue-i18n'
import { UtilityService } from '@/api/utils'
import LocaleSelect from '@/components/LocaleSelect.vue'
import QuestionEditor from '@/components/QuestionEditor.vue'
import RequiredSpan from '@/components/RequiredSpan.vue'

const props = defineProps<{
  loading?: boolean
  readonly?: boolean
}>()

const manuscript = defineModel<ManuscriptRecord>({ required: true })

const { t } = useI18n()
const $q = useQuasar()

// PLS generation and translation state
const PLSLoading = ref(false)
const translateLoading = ref(false)

const enablePLSPrompt = computed(() => {
  if (!manuscript.value) {
    return false
  }

  const locale = manuscript.value.pls_source_language

  return (
    !props.readonly
    && (manuscript.value[`pls_${locale}`] === '')
    && manuscript.value.abstract.length > 250
    && !PLSLoading.value
  )
})

const enableTranslatePLS = computed(() => {
  if (!manuscript.value) {
    return false
  }

  const sourceLanguage = manuscript.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'

  return (
    !props.readonly
    && (manuscript.value[`pls_${sourceLanguage}`] !== '')
    && (manuscript.value[`pls_${targetLanguage}`] === '')
    && !translateLoading.value
    && !PLSLoading.value
  )
})

const translateDisabledTooltip = computed(() => {
  if (!manuscript.value) {
    return ''
  }

  const sourceLanguage = manuscript.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'

  if (manuscript.value[`pls_${sourceLanguage}`] === '') {
    return t('mrf.source-pls-required-for-translation')
  }

  if (manuscript.value[`pls_${targetLanguage}`] !== '') {
    return t('mrf.target-pls-already-exists-erase-to-translate')
  }

  return ''
})

const plsDisabledTooltip = computed(() => {
  if (!manuscript.value) {
    return ''
  }

  const locale = manuscript.value.pls_source_language

  if (manuscript.value[`pls_${locale}`] !== '') {
    return t('mrf.pls-already-generated-erase-it-to-generate-a-new-one')
  }

  if (manuscript.value.abstract.length < 250) {
    return t('mrf.abstract-must-be-at-least-250-characters')
  }

  return ''
})

// Author approval logic
const hasCompletedPLS = computed(() => {
  if (!manuscript.value) {
    return false
  }

  const sourceLanguage = manuscript.value.pls_source_language
  return manuscript.value[`pls_${sourceLanguage}`] !== ''
})

const showApprovalRequired = computed(() => {
  return hasCompletedPLS.value && !manuscript.value?.pls_approved_by_author && !props.readonly
})

async function generatePLS() {
  PLSLoading.value = true
  if (!manuscript.value?.abstract)
    return
  if (manuscript.value?.abstract === '')
    return

  // which pls are we generating - this is based on
  const locale = manuscript.value?.pls_source_language

  manuscript.value[`pls_${locale}`] = t(
    'mrf.generating-pls-please-be-patient',
  )

  await UtilityService.generatePls(manuscript.value.abstract, locale)
    .then((response) => {
      if (!manuscript.value)
        return

      manuscript.value[`pls_${locale}`] = response.data.pls

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
  if (!manuscript.value)
    return

  const sourceLanguage = manuscript.value.pls_source_language
  const targetLanguage = sourceLanguage === 'en' ? 'fr' : 'en'
  const sourcePLS = manuscript.value[`pls_${sourceLanguage}`]

  if (!sourcePLS || sourcePLS === '')
    return

  manuscript.value[`pls_${targetLanguage}`] = t(
    'mrf.translating-pls-please-be-patient',
  )

  await UtilityService.translatePls(sourcePLS, targetLanguage)
    .then((response) => {
      if (!manuscript.value)
        return

      manuscript.value[`pls_${targetLanguage}`] = response.data.pls

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
  <!-- Section Header -->
  <div class="q-mb-lg">
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h6 text-primary text-weight-medium">
          {{ $t('common.plain-language-summary') }}
        </div>
        <div class="text-body2  q-mt-xs">
          {{ $t('mrf.pls-text') }}
        </div>
      </div>
    </div>

    <!-- Source Language Selection Card -->
    <q-card flat bordered class="q-mb-lg">
      <q-card-section>
        <div class="text-subtitle2 text-primary q-mb-xs">
          <q-icon name="mdi-translate" class="q-mr-xs" />
          {{ $t('mrf.pls-source-language-title') }}<RequiredSpan />
        </div>
        <div class="text-body2  q-mb-md">
          {{ $t('mrf.pls-source-language-description') }}
        </div>
        <LocaleSelect
          v-model="manuscript.pls_source_language"
          :label="t('mrf.pls-locale')"
          outlined
          dense
          class="q-mb-md"
        />
      </q-card-section>
    </q-card>

    <!-- AI Tools Section -->
    <q-card flat bordered class="q-mb-lg">
      <q-card-section>
        <div class="row items-center justify-between">
          <div class="col">
            <div class="text-subtitle2 text-primary q-mb-xs">
              <q-icon name="mdi-robot" class="q-mr-xs" />
              {{ $t('mrf.ai-powered-tools') }}
            </div>
            <div class="text-body2 text-grey-7">
              {{ $t('mrf.pls-help-ai-text') }}
            </div>
          </div>
        </div>

        <div class="row q-gutter-sm q-mt-md">
          <q-btn
            color="primary"
            :label="PLSLoading ? $t('mrf.generating-pls-please-be-patient') : $t('mrf.generate-pls')"
            :icon="PLSLoading ? 'mdi-loading mdi-spin' : 'mdi-brain'"
            unelevated
            outline
            rounded
            :disable="!enablePLSPrompt"
            :loading="PLSLoading"
            class="q-px-lg"
            @click="generatePLS"
          >
            <q-tooltip v-if="!enablePLSPrompt" class="text-body2">
              {{ plsDisabledTooltip }}
            </q-tooltip>
          </q-btn>

          <q-btn
            color="secondary"
            :label="translateLoading ? $t('mrf.translating-pls-please-be-patient') : $t('mrf.translate-pls')"
            :icon="translateLoading ? 'mdi-loading mdi-spin' : 'mdi-translate'"
            unelevated
            outline
            rounded
            :disable="!enableTranslatePLS"
            :loading="translateLoading"
            class="q-px-lg"
            @click="translatePLS"
          >
            <q-tooltip v-if="!enableTranslatePLS" class="text-body2">
              {{ translateDisabledTooltip }}
            </q-tooltip>
          </q-btn>
        </div>
      </q-card-section>
    </q-card>
  </div>

  <!-- Main PLS Content and Approval Section (Consolidated) -->
  <q-card flat bordered class="q-mb-lg">
    <!-- Content Section Header -->
    <q-card-section class="q-pb-sm">
      <div class="text-subtitle1 text-weight-medium text-grey-8 q-mb-xs">
        <q-icon name="mdi-text-box-outline" class="q-mr-xs" />
        {{ $t('mrf.pls-content-title') }}
      </div>
      <div class="text-body2 text-grey-6">
        {{ $t('mrf.pls-content-description') }}
      </div>
    </q-card-section>
    <!-- Dynamic Editor Order Based on Source Language -->
    <template v-if="manuscript.pls_source_language === 'en'">
      <!-- English (Primary) -->
      <q-card-section class="q-pb-sm">
        <QuestionEditor
          v-model="manuscript.pls_en"
          :title="$t('common.plain-language-summary-en')"
          :disable="loading || PLSLoading"
          :readonly="readonly"
          :required="true"
          hide-title
        />
      </q-card-section>

      <!-- French (Optional) -->
      <q-card-section class="q-pb-sm">
        <QuestionEditor
          v-model="manuscript.pls_fr"
          :title="$t('common.plain-language-summary-fr')"
          :disable="loading || PLSLoading"
          :readonly="readonly"
          :required="false"
          hide-title
        />
      </q-card-section>
    </template>

    <template v-else>
      <!-- French (Primary) -->
      <q-card-section class="q-pb-sm">
        <QuestionEditor
          v-model="manuscript.pls_fr"
          :title="$t('common.plain-language-summary-fr')"
          :disable="loading || PLSLoading"
          :readonly="readonly"
          :required="true"
          hide-title
        />
      </q-card-section>

      <!-- English (Optional) -->
      <q-card-section class="q-pb-sm">
        <QuestionEditor
          v-model="manuscript.pls_en"
          :title="$t('common.plain-language-summary-en')"
          :disable="loading || PLSLoading"
          :readonly="readonly"
          :required="false"
          hide-title
        />
      </q-card-section>
    </template>

    <!-- Author Approval Section (integrated within the same card) -->
    <q-card-section class="q-pl-lg">
      <div class="text-subtitle1 text-primary text-weight-medium text-grey-8 q-mb-xs">
        <q-icon name="mdi-check-circle-outline" class="q-mr-xs" />
        {{ $t('mrf.pls-author-approval-title') }}
      </div>
      <div class="text-body2  q-mb-md">
        {{ $t('mrf.pls-author-approval-description') }}
      </div>

      <q-checkbox
        v-model="manuscript.pls_approved_by_author"
        :label="$t('mrf.pls-approved-by-author-label')"
        :disable="readonly || loading || !hasCompletedPLS"
        color="primary"
        size="md"
        class="q-mb-sm"
      />

      <div v-if="manuscript.pls_approved_by_author && hasCompletedPLS" class="row items-center q-mt-sm">
        <q-icon name="mdi-check-circle" color="positive" size="sm" class="q-mr-sm" />
        <span class="text-body2 text-positive">{{ $t('mrf.pls-approved-confirmation') }}</span>
      </div>

      <div v-else-if="!hasCompletedPLS" class="row items-center q-mt-sm">
        <q-icon name="mdi-information-outline" color="grey-6" size="sm" class="q-mr-sm" />
        <span class="text-body2 text-grey-6">{{ $t('mrf.pls-approval-disabled-no-content') }}</span>
      </div>

      <div v-else-if="showApprovalRequired" class="row items-center q-mt-sm">
        <q-icon name="mdi-alert-circle" color="warning" size="sm" class="q-mr-sm" />
        <span class="text-body2 text-warning">{{ $t('mrf.pls-approval-required-warning') }}</span>
      </div>
    </q-card-section>
  </q-card>
</template>
