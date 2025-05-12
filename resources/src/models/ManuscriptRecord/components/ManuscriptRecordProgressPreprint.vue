<script setup lang="ts">
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'
import { useQuasar } from 'quasar'
import ManuscriptStatusSpan from '../components/ManuscriptStatusSpan.vue'
import WithdrawManuscriptDialog from '../components/WithdrawManuscriptDialog.vue'
import SubmittedToPreprintDialog from './SubmittedToPreprintDialog.vue'

const manuscriptRecord = defineModel<ManuscriptRecordResource>({ required: true })

const $q = useQuasar()
const { t } = useI18n()

const createdSubtitle = computed(() => {
  return (
    `${t('common.created-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.created_at).value}`
  )
})

const createdColor = computed(() => {
  return 'primary'
})

const submittedReviewSubtitle = computed(() => {
  if (manuscriptRecord.value.data.sent_for_review_at === null) {
    return t('common.pending')
  }
  return (
    `${t('common.submitted-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.sent_for_review_at).value}`
  )
})

const submittedReviewColor = computed(() => {
  if (manuscriptRecord.value === null)
    return 'grey-7'
  if (manuscriptRecord.value.data.sent_for_review_at === null)
    return 'yellow-8'
  return 'primary'
})

const managementReviewSubtitle = computed(() => {
  if (manuscriptRecord.value.data.sent_for_review_at === null) {
    return t('common.pending')
  }
  if (manuscriptRecord.value.data.reviewed_at === null) {
    return t('common.in-progress')
  }
  return (
    `${t('common.completed-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.reviewed_at).value}`
  )
})

const managementReviewColor = computed(() => {
  if (manuscriptRecord.value.data.sent_for_review_at === null)
    return 'yellow-8'
  if (manuscriptRecord.value.data.reviewed_at === null)
    return 'secondary'
  return 'primary'
})

const managementReviewIcon = computed(() => {
  if (manuscriptRecord.value?.data.status === 'in_review') {
    return 'mdi-progress-check'
  }
  return 'mdi-thumb-up-outline'
})

const submittedToPreprintSubtitle = computed(() => {
  if (manuscriptRecord.value.data.submitted_to_journal_on === null) {
    return t('common.pending')
  }
  return (
    `${t('common.submitted-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.submitted_to_journal_on).value}`
  )
})
const submittedToPreprintColor = computed(() => {
  if (manuscriptRecord.value === null)
    return 'grey-7'
  if (manuscriptRecord.value.data.submitted_to_journal_on === null)
    return 'yellow-8'
  return 'primary'
})

const withdrawnSubtitle = computed(() => {
  if (
    manuscriptRecord.value === null
    || manuscriptRecord.value.data.withdrawn_on === null
  ) {
    return ''
  }
  return (
    `${t('common.withdrawn-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.withdrawn_on).value}`
  )
})

// action dialogs and methods

const showSubmitToPreprintDialog = ref(false)

function submitToPreprint(record: ManuscriptRecordResource) {
  showSubmitToPreprintDialog.value = false
  updateManuscriptandNotify(record)
}

// withdraw manuscript
const showWithdrawManuscriptDialog = ref(false)

function withdrawManuscript(record: ManuscriptRecordResource) {
  showWithdrawManuscriptDialog.value = false
  updateManuscriptandNotify(record)
}

function updateManuscriptandNotify(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  $q.notify({
    message: t('manuscript-progress-view.manuscript-status-updated'),
    color: 'positive',
    icon: 'mdi-check',
  })
}
</script>

<template>
  <q-timeline v-if="manuscriptRecord" color="primary" class="q-px-md">
    <q-timeline-entry heading>
      <div class="text-h4 text-primary">
        {{ $t('manuscript-progress-view.title') }}
      </div>
      <div
        class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
      >
        <ManuscriptStatusSpan :status="manuscriptRecord.data.status" />
      </div>
    </q-timeline-entry>
    <q-timeline-entry
      class="q-mx-lg"
      icon="mdi-plus"
      :title="$t('manuscript-progress-view.manuscript-record-created')"
      :subtitle="createdSubtitle"
      :color="createdColor"
    >
      <p>
        {{ $t('manuscript-progress-view.created-details') }}
      </p>
    </q-timeline-entry>
    <q-timeline-entry
      class="q-mx-lg"
      icon="mdi-send-check-outline"
      :title="$t('manuscript-progress-view.submit-management')"
      :subtitle="submittedReviewSubtitle"
      :color="submittedReviewColor"
    >
      <p>
        {{ $t('manuscript-progress-view.submit-management-details') }}
      </p>
    </q-timeline-entry>
    <q-timeline-entry
      class="q-mx-lg"
      :icon="managementReviewIcon"
      :title="$t('manuscript-progress-view.completed-title')"
      :subtitle="managementReviewSubtitle"
      :color="managementReviewColor"
    >
      <p>
        {{ $t('manuscript-progress-view.completed-preprint-details') }}
      </p>
    </q-timeline-entry>
    <q-timeline-entry
      v-if="manuscriptRecord.data.status !== 'withdrawn'"
      class="q-mx-lg"
      icon="mdi-check-all"
      :title="$t('manuscript-progress-view.submitted-to-preprint')"
      :subtitle="submittedToPreprintSubtitle"
      :color="submittedToPreprintColor"
    >
      <p>
        {{ $t('manuscript-progress-view.submitted-to-preprint-details') }}
      </p>
      <p v-if="manuscriptRecord.data.preprint_url !== ''">
        <span class="text-primary">
          {{ $t('manuscript-progress-view.preprint-url') }}:
        </span>
        <span><a :href="manuscriptRecord.data.preprint_url" target="_blank">{{ manuscriptRecord.data.preprint_url }}</a></span>
        <span class="q-ml-sm"><CopyToClipboardButton :text="manuscriptRecord.data.preprint_url" size="sm" /></span>
      </p><div class="row q-gutter-md">
        <q-btn
          v-if="
            manuscriptRecord.data.status !== 'accepted'
              && manuscriptRecord.data.can_attach_manuscript
          "
          color="primary"
          :label="$t('manuscript-progress-view.submit-preprint-title')"
          :disable="
            manuscriptRecord.data.status === 'draft'
              || manuscriptRecord.data.status === 'in_review'
          "
          @click="showSubmitToPreprintDialog = true"
        />
        <q-btn
          v-if="
            manuscriptRecord.data.status !== 'accepted'
              && manuscriptRecord.data.can_attach_manuscript
          "
          color="negative"
          outline
          :label="$t('manuscript-progress-view.withdraw-manuscript')"
          :disable="
            manuscriptRecord.data.status === 'draft'
              || manuscriptRecord.data.status === 'in_review'
          "
          @click="showWithdrawManuscriptDialog = true"
        />
        <q-btn
          v-if="manuscriptRecord.data.preprint_url !== ''"
          color="primary"
          :label="
            $t('manuscript-progress-view.go-to-the-preprint')
          "
          :href="manuscriptRecord.data.preprint_url"
          target="_blank"
          icon="mdi-web"
        />
        <q-btn
          v-if="
            manuscriptRecord.data.status === 'accepted'
              && manuscriptRecord.data.can_resubmit_preprint
          "
          color="primary"
          outline
          :label="$t('manuscript-progress-view.edit-preprint-title')"
          icon="mdi-pencil"
          @click="showSubmitToPreprintDialog = true"
        />
      </div>
    </q-timeline-entry>
    <q-timeline-entry
      v-if="manuscriptRecord.data.status === 'withdrawn'"
      class="q-mx-lg"
      icon="mdi-sign-caution"
      :title="$t('manuscript-progress-view.withdrawn-by-applicant')"
      :subtitle="withdrawnSubtitle"
      color="red"
    >
      <p>
        {{ $t('manuscript-progress-view.withdrawn-details') }}
      </p>
    </q-timeline-entry>
    <WithdrawManuscriptDialog
      v-if="showWithdrawManuscriptDialog"
      v-model="showWithdrawManuscriptDialog"
      :manuscript-record-id="manuscriptRecord.data.id"
      @updated="withdrawManuscript"
    />
    <SubmittedToPreprintDialog
      v-if="showSubmitToPreprintDialog"
      v-model="showSubmitToPreprintDialog"
      :manuscript-record-id="manuscriptRecord.data.id"
      :url="manuscriptRecord.data.preprint_url ?? undefined"
      :submitted-on="manuscriptRecord.data.submitted_to_journal_on ?? undefined"
      @updated="submitToPreprint"
    />
  </q-timeline>
</template>

<style scoped></style>
