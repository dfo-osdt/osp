<script setup lang="ts">
import type { Ref } from 'vue'
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import AcceptedByJournalDialog from '../components/AcceptedByJournalDialog.vue'
import ManuscriptStatusSpan from '../components/ManuscriptStatusSpan.vue'
import SubmittedToJournalDialog from '../components/SubmittedToJournalDialog.vue'
import SubmitToPubTeamDialog from '../components/SubmitToPubTeamDialog.vue'
import WithdrawManuscriptDialog from '../components/WithdrawManuscriptDialog.vue'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'

const props = defineProps<{
  id: number
}>()
const emit = defineEmits<{
  (e: 'updateManuscript', manuscript: ManuscriptRecordResource): void
}>()
const $q = useQuasar()
const { t } = useI18n()

const manuscriptRecord: Ref<ManuscriptRecordResource | null> = ref(null)

const createdSubtitle = computed(() => {
  if (manuscriptRecord.value === null) {
    return ''
  }
  return (
    `${t('common.created-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.created_at).value}`
  )
})

const createdColor = computed(() => {
  if (manuscriptRecord.value === null) {
    return 'grey-7'
  }
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

const submittedToJournalSubtitle = computed(() => {
  if (manuscriptRecord.value === null) {
    return ''
  }
  if (manuscriptRecord.value.data.submitted_to_journal_on === null) {
    return t('common.pending')
  }
  return (
    `${t('common.submitted-on')
    } ${
      useLocaleDate(manuscriptRecord.value.data.submitted_to_journal_on).value}`
  )
})

const submittedToJournalColor = computed(() => {
  if (manuscriptRecord.value === null)
    return 'grey-7'
  if (manuscriptRecord.value.data.submitted_to_journal_on === null)
    return 'yellow-8'
  return 'primary'
})

const acceptedToJournalSubtitle = computed(() => {
  if (manuscriptRecord.value === null) {
    return ''
  }
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

async function getManuscriptRecord() {
  await ManuscriptRecordService.find(props.id)
    .then((response) => {
      manuscriptRecord.value = response
      emit('updateManuscript', manuscriptRecord.value)
    })
    .catch((error) => {
      console.error(error)
    })
}

// action dialogs and methods

// submitted to journal dialog
const showSubmittedToJournalDialog = ref(false)

function submittedToJournal(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  showSubmittedToJournalDialog.value = false
  showUpdatedNotification(record)
}

// accept for publication
const showAcceptedByJournalDialog = ref(false)

function acceptedToJournal(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  showAcceptedByJournalDialog.value = false
  showUpdatedNotification(record)
}
// withdraw manuscript
const showWithdrawManuscriptDialog = ref(false)

function withdrawManuscript(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  showWithdrawManuscriptDialog.value = false
  showUpdatedNotification(record)
}

// submit to publication team
const showSubmitToPubTeamDialog = ref(false)

function submitToPubTeam(record: ManuscriptRecordResource) {
  manuscriptRecord.value = record
  showSubmitToPubTeamDialog.value = false
  showUpdatedNotification(record)
}

function showUpdatedNotification(record: ManuscriptRecordResource) {
  emit('updateManuscript', record)
  $q.notify({
    message: t('manuscript-progress-view.manuscript-status-updated'),
    color: 'positive',
    icon: 'mdi-check',
  })
}

onMounted(() => {
  getManuscriptRecord()
})
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
    <template v-if="manuscriptRecord.data.type === 'primary'">
      <q-timeline-entry
        v-if="manuscriptRecord.data.status !== 'withdrawn'"
        class="q-mx-lg"
        icon="mdi-send-check-outline"
        :title="$t('manuscript-progress-view.submit-pub-title')"
        :subtitle="submittedToJournalSubtitle"
        :color="submittedToJournalColor"
      >
        <p>
          {{ $t('manuscript-progress-view.submit-pub-details') }}
        </p>
        <div>
          <q-btn
            v-if="
              manuscriptRecord.data.submitted_to_journal_on
                === null && manuscriptRecord.data.can_attach_manuscript
            "
            color="primary"
            :label="$t('manuscript-progress-view.mark-as-submitted')"
            :disable="
              manuscriptRecord.data.status === 'in_review'
                || manuscriptRecord.data.status === 'draft'
            "
            @click="showSubmittedToJournalDialog = true"
          />
        </div>
      </q-timeline-entry>
      <q-timeline-entry
        v-if="manuscriptRecord.data.status !== 'withdrawn'"
        class="q-mx-lg"
        icon="mdi-check-all"
        :title="$t('manuscript-progress-view.accepted-title')"
        :subtitle="acceptedToJournalSubtitle"
        :color="acceptedToJournalColor"
      >
        <p>
          {{ $t('manuscript-progress-view.accepted-details') }}
        </p>
        <div class="row q-gutter-md">
          <q-btn
            v-if="
              manuscriptRecord.data.status !== 'accepted'
                && manuscriptRecord.data.can_attach_manuscript
            "
            color="primary"
            :label="$t('manuscript-progress-view.accepted-title')"
            :disable="
              manuscriptRecord.data.status === 'draft'
                || manuscriptRecord.data.status === 'in_review'
            "
            @click="showAcceptedByJournalDialog = true"
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
    </template>
    <template v-if="manuscriptRecord.data.type === 'secondary'">
      <q-timeline-entry
        v-if="manuscriptRecord.data.status !== 'withdrawn'"
        class="q-mx-lg"
        icon="mdi-check-all"
        :title="$t('manuscript-progress-view.submisison-to-dfo-title')"
        :subtitle="acceptedToJournalSubtitle"
        :color="acceptedToJournalColor"
      >
        <p>
          {{ $t('manuscript-progress-view.submission-to-dfo-details') }}
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
    </template>
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
    <SubmittedToJournalDialog
      v-if="showSubmittedToJournalDialog"
      v-model="showSubmittedToJournalDialog"
      :manuscript-record-id="manuscriptRecord.data.id"
      @updated="submittedToJournal"
    />
    <AcceptedByJournalDialog
      v-if="showAcceptedByJournalDialog"
      v-model="showAcceptedByJournalDialog"
      :manuscript-record="manuscriptRecord.data"
      @updated="acceptedToJournal"
    />
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
