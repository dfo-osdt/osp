<script setup lang="ts">
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import ManuscriptStatusSpan from '../components/ManuscriptStatusSpan.vue'
import SubmitToPubTeamDialog from '../components/SubmitToPubTeamDialog.vue'
import WithdrawManuscriptDialog from '../components/WithdrawManuscriptDialog.vue'

const manuscriptRecord = defineModel<ManuscriptRecordResource>({ required: true })

const $q = useQuasar()
const { t } = useI18n()

const publicationsEmail = 'Publications.XNCR@dfo-mpo.gc.ca'

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
  if (manuscriptRecord.value === null) {
    return ''
  }
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
  if (manuscriptRecord.value === null) {
    return ''
  }
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
  if (manuscriptRecord.value === null)
    return 'grey-7'
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

const acceptedToJournalSubtitle = computed(() => {
  if (manuscriptRecord.value.data.accepted_on === null) {
    return t('common.pending')
  }
  return (
    `${t('common.accepted-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.accepted_on).value}`
  )
})

const acceptedToJournalColor = computed(() => {
  if (manuscriptRecord.value === null)
    return 'grey-7'
  if (manuscriptRecord.value.data.accepted_on === null)
    return 'yellow-8'
  return 'primary'
})

const withdrawnSubtitle = computed(() => {
  if (
    manuscriptRecord.value.data.withdrawn_on === null
  ) {
    return ''
  }
  return (
    `${t('common.withdrawn-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.withdrawn_on).value}`
  )
})

// withdraw manuscript
const showWithdrawManuscriptDialog = ref(false)

function withdrawManuscript(record: ManuscriptRecordResource) {
  showWithdrawManuscriptDialog.value = false
  updateManuscriptandNotify(record)
}

// submit to publication team
const showSubmitToPubTeamDialog = ref(false)

function submitToPubTeam(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  updateManuscriptandNotify(record)
}

function updateManuscriptandNotify(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  showSubmitToPubTeamDialog.value = false
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
        {{ $t('manuscript-progress-view.completed-details') }}
      </p>
    </q-timeline-entry>
    <q-timeline-entry
      v-if="manuscriptRecord.data.status !== 'withdrawn'"
      class="q-mx-lg"
      icon="mdi-check-all"
      :title="$t('manuscript-progress-view.submission-to-dfo-title')"
      :subtitle="acceptedToJournalSubtitle"
      :color="acceptedToJournalColor"
    >
      <p>
        {{ $t('manuscript-progress-view.submission-to-dfo-details') }}
      </p>
      <p>
        {{ $t('manuscript-progress-view.publication-numbers-instruction') }}
        <a
          href="https://intranet.ent.dfo-mpo.ca/mpo/sites/dfo-mpo/files/publishing-form-formulaire-publication-eng_0.pdf"
          target="_blank"
        >{{ $t('common.english') }}</a>
        |
        <a
          href="https://intranet.ent.dfo-mpo.ca/mpo/sites/dfo-mpo/files/publishing-form-formulaire-publication-fra.pdf"
          target="_blank"
        >{{ $t('common.french') }}</a>)
        {{ $t('manuscript-progress-view.send-form-to') }}
        <a :href="`mailto:${publicationsEmail}`">{{ publicationsEmail }}</a>.
      </p>
      <div class="row q-gutter-md">
        <q-btn
          v-if="
            manuscriptRecord.data.status !== 'accepted'
              && manuscriptRecord.data.can_attach_manuscript
          "
          color="primary"
          :label="$t('manuscript-progress-view.submit-title')"
          :disable="
            manuscriptRecord.data.status === 'draft'
              || manuscriptRecord.data.status === 'in_review'
          "
          @click="showSubmitToPubTeamDialog = true"
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
          v-if="manuscriptRecord.data.publication"
          color="primary"
          :label="
            $t('manuscript-progress-view.go-to-the-publication')
          "
          :to="`/publication/${manuscriptRecord.data.publication?.data.id}`"
          icon-right="mdi-arrow-right"
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
        {{ `${$t('manuscript-progress-view.withdrawn-details')}${manuscriptRecord.data.withdraw_reason}` }}
      </p>
    </q-timeline-entry>
    <WithdrawManuscriptDialog
      v-if="showWithdrawManuscriptDialog"
      v-model="showWithdrawManuscriptDialog"
      :manuscript-record-id="manuscriptRecord.data.id"
      @updated="withdrawManuscript"
    />
    <SubmitToPubTeamDialog
      v-if="showSubmitToPubTeamDialog"
      v-model="showSubmitToPubTeamDialog"
      :manuscript-record="manuscriptRecord.data"
      @updated="submitToPubTeam"
    />
  </q-timeline>
</template>

<style scoped></style>
