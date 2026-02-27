<script setup lang="ts">
import type {
  ManagementReviewStepResource,
} from '../ManagementReviewStep'
import type { ManuscriptRecordResource } from '@/models/ManuscriptRecord/ManuscriptRecord'
import type { MediaResourceList } from '@/models/Media/Media'
import { useQuasar } from 'quasar'
import QuestionEditor from '@/components/QuestionEditor.vue'
import SafeHtml from '@/components/SafeHtml.vue'
import SensitivityLabelChip from '@/components/SensitivityLabelChip.vue'
import WarnOnUnsavedChanges from '@/components/WarnOnUnsavedChanges.vue'
import { useLocaleDate } from '@/composables/useLocaleDate'
import ManagementReviewStepStatusSpan from '../components/ManagementReviewStepStatusSpan.vue'
import {
  ManagementReviewStepService,
} from '../ManagementReviewStep'
import ManagementReviewStepDecisionSpan from './ManagementReviewStepDecisionSpan.vue'
import SubmitDecisionDialog from './SubmitDecisionDialog.vue'
import WithdrawManuscriptReplyDialog from './WithdrawManuscriptReplyDialog.vue'

const props = defineProps<{
  modelValue: ManagementReviewStepResource
  manuscriptRecord: ManuscriptRecordResource | null
  manuscriptFiles: MediaResourceList | null
}>()
const emit = defineEmits<{
  (event: 'update:modelValue', value: ManagementReviewStepResource): void
  (event: 'decision', value: ManagementReviewStepResource): void
}>()
const { t } = useI18n()
const $q = useQuasar()

const managementStep = useVModel(props, 'modelValue', emit)

const canUpdate = computed(() => {
  return !!managementStep.value.can?.update
})

const userName = computed(() => {
  if (!managementStep.value.data.user) {
    return t('common.unknown')
  }
  const { first_name, last_name } = managementStep.value.data.user?.data
  return `${first_name} ${last_name}`
})

const icon = computed(() => {
  switch (managementStep.value.data.status) {
    case 'completed':
      return 'mdi-account-check-outline'
    case 'pending':
      return 'mdi-account-clock-outline'
    case 'reassign':
      return 'mdi-account-arrow-right-outline'
    case 'on_hold':
      return 'mdi-timer-pause-outline'
    default:
      return 'mdi-account-question-outline'
  }
})

const color = computed(() => {
  switch (managementStep.value.data.status) {
    case 'completed':
      switch (managementStep.value.data.decision) {
        case 'complete':
          return 'primary'
        case 'revision':
          return 'orange-8'
        case 'withdrawn':
          return 'red'
        default:
          return 'primary'
      }
    case 'pending':
    case 'on_hold':
      return 'yellow-8'
    case 'reassign':
      return 'secondary'
    default:
      return 'primary'
  }
})

const completedAtDate = useLocaleDate(managementStep.value.data.completed_at)
const decisionExpectedByDate = useLocaleDate(
  managementStep.value.data.decision_expected_by,
)

// Get the latest PDF file (first in the list)
const latestPdfFile = computed(() => {
  if (!props.manuscriptFiles || props.manuscriptFiles.data.length === 0) {
    return null
  }
  return props.manuscriptFiles.data[0]
})

// Check if public interest section should be displayed
const showPublicInterest = computed(() => {
  if (!props.manuscriptRecord) {
    return false
  }
  return (
    props.manuscriptRecord.data.potential_public_interest
    || (props.manuscriptRecord.data.public_interest_information
      && props.manuscriptRecord.data.public_interest_information.trim() !== '')
  )
})

const isDirty = ref(false)
watch(
  () => managementStep.value.data.comments,
  () => {
    isDirty.value = true
  },
)

async function save() {
  await ManagementReviewStepService.update(managementStep.value.data)

  $q.notify({
    message: t('common.saved'),
    type: 'positive',
  })

  isDirty.value = false
}

const submitDecisionDialog = ref(false)
async function showDecisionDialog() {
  await save()
  submitDecisionDialog.value = true
}
function decisionSubmitted(decision: ManagementReviewStepResource) {
  managementStep.value = decision
  submitDecisionDialog.value = false
  emit('decision', decision)
}

// Author Decision section
async function submitDecision() {
  if (managementStep.value.data.comments === '') {
    $q.notify({
      message: t('review-step.comments-required'),
      type: 'negative',
    })
    return
  }
  const resposne = await ManagementReviewStepService.response(
    managementStep.value.data,
  )
  decisionSubmitted(resposne)
}

const withdrawManuscriptDialog = ref(false)
function withdrawManuscript() {
  withdrawManuscriptDialog.value = true
}
</script>

<template>
  <q-timeline-entry class="q-mx-lg" :icon="icon" :color="color">
    <template v-if="canUpdate" #title>
      {{
        managementStep.data.status === 'on_hold'
          ? t('review-step.your-response', [userName])
          : t('review-step.your-review', [userName])
      }}
    </template>
    <template v-else #title>
      {{
        managementStep.data.status === 'on_hold'
          ? t('review-step.response-by', [userName])
          : t('review-step.review-by', [userName])
      }}
    </template>
    <template #subtitle>
      <ManagementReviewStepStatusSpan
        :status="managementStep.data.status"
      />
      <span v-if="completedAtDate"> - {{ completedAtDate }}</span>
      <span v-else-if="managementStep.data.status === 'pending' && decisionExpectedByDate !== ''">
        {{
          ` - ${t('common.decision-expected-by')}: ${decisionExpectedByDate}`
        }}</span>
    </template>
    <template v-if="canUpdate">
      <q-card
        v-if="managementStep.data.status === 'on_hold'"
        class="q-pa-md"
        bordered
        flat
      >
        <div class="text-body1 text-weight-medium text-primary">
          {{ t('management-review-response.title') }}
        </div>
        <p>
          {{ t('management-review-response.subtitle') }}
        </p>
        <QuestionEditor
          v-model="managementStep.data.comments"
          :title="t('author-comments.title')"
        >
          <p>
            {{ t('author-comments.description') }}
          </p>
        </QuestionEditor>
        <q-card-actions align="right">
          <q-btn
            color="primary"
            outline
            :label="t('review-step.save-comments')"
            @click="save"
          />
          <q-btn
            icon="mdi-message-reply-text-outline"
            color="primary"
            :label="t('review-step.reply')"
            :disable="managementStep.data.comments === ''"
            @click="submitDecision()"
          />
        </q-card-actions>
        <q-separator />
        <q-card-section>
          <div class="text-grey-7 text-overline text-uppercase q-mb-sm">
            {{ t('common.other-actions') }}
          </div>
          <q-btn
            icon="mdi-book-remove-outline"
            color="primary"
            outline
            :disabled="managementStep.data.comments === ''"
            :label="
              t('manuscript-progress-view.withdraw-manuscript')
            "
            @click="withdrawManuscript()"
          />
        </q-card-section>
        <WithdrawManuscriptReplyDialog
          v-if="withdrawManuscriptDialog"
          v-model="withdrawManuscriptDialog"
          :management-review-step="managementStep"
          @updated="decisionSubmitted"
        />
      </q-card>
      <q-card v-else flat bordered>
        <q-card-section class="q-pb-none">
          <div class="text-body1 text-weight-medium text-primary">
            {{ t('management-review-guidelines.title') }}
          </div>
          <p>
            {{ t('management-review-guidelines.subtitle') }}
          </p>
        </q-card-section>
        <q-card-section>
          <!-- Quick Review Section -->
          <q-card flat bordered class="q-mb-lg bg-grey-1">
            <q-card-section>
              <div class="text-weight-bold text-uppercase text-grey-8 q-mb-sm">
                {{ t('management-review-quick-info.title') }}
              </div>

              <!-- PDF Download Section -->
              <div class="q-mb-md">
                <div class="text-weight-medium text-grey-8 q-mb-xs">
                  {{ t('management-review-quick-info.manuscript-pdf') }}
                </div>
                <q-card v-if="latestPdfFile" bordered flat>
                  <q-item>
                    <q-item-section avatar>
                      <q-icon name="mdi-file-pdf-box" color="primary" size="md" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>{{ latestPdfFile.data.file_name }}</q-item-label>
                      <q-item-label caption>
                        {{ latestPdfFile.data.size_bytes / 1000 }}
                        {{ t('common.kb-uploaded-on') }}
                        {{ new Date(latestPdfFile.data.created_at).toLocaleString() }}
                      </q-item-label>
                    </q-item-section>
                    <q-item-section v-if="latestPdfFile.data.sensitivity_label === 'Protected A'" side>
                      <SensitivityLabelChip :sensitivity="latestPdfFile.data.sensitivity_label" />
                    </q-item-section>
                    <q-item-section side>
                      <q-btn
                        icon="mdi-file-download-outline"
                        color="primary"
                        :disable="!latestPdfFile.can?.download"
                        :href="latestPdfFile.can?.download ? `api/manuscript-records/${managementStep.data.manuscript_record_id}/files/${latestPdfFile.data.uuid}?download=true` : undefined"
                      >
                        <q-tooltip v-if="!latestPdfFile.can?.download">
                          {{ t('common.cant-download') }}
                        </q-tooltip>
                      </q-btn>
                    </q-item-section>
                  </q-item>
                </q-card>
                <div v-else class="text-grey-6 text-italic">
                  {{ t('management-review-quick-info.no-pdf-uploaded') }}
                </div>
              </div>

              <!-- Public Interest Section -->
              <div>
                <div class="text-weight-medium text-grey-8 q-mb-xs">
                  {{ t('management-review-quick-info.public-interest') }}
                </div>
                <div v-if="showPublicInterest">
                  <div class="q-mb-sm">
                    <q-icon
                      :name="manuscriptRecord?.data.potential_public_interest ? 'mdi-check-circle' : 'mdi-close-circle'"
                      :color="manuscriptRecord?.data.potential_public_interest ? 'positive' : 'grey-6'"
                      size="sm"
                      class="q-mr-xs"
                    />
                    <span :class="manuscriptRecord?.data.potential_public_interest ? 'text-positive text-weight-medium' : 'text-grey-6'">
                      {{ manuscriptRecord?.data.potential_public_interest ? t('common.yes') : t('common.no') }}
                    </span>
                  </div>
                  <q-card
                    v-if="manuscriptRecord?.data.public_interest_information"
                    bordered
                    flat
                    class="text-grey-8 q-pa-sm"
                  >
                    <SafeHtml :html="manuscriptRecord?.data.public_interest_information" />
                  </q-card>
                </div>
                <div v-else class="text-grey-6 text-italic">
                  {{ t('management-review-quick-info.no-public-interest') }}
                </div>
              </div>
            </q-card-section>
          </q-card>

          <ul class="q-mb-md">
            <li>
              {{ t('management-review-guidelines.tip-one') }}
            </li>
            <q-btn
              :label="t('common.go-to-manuscript-form')"
              icon="mdi-arrow-left"
              outline
              color="primary"
              class="q-my-md"
              :to="`/manuscript/${managementStep.data.manuscript_record_id}/form`"
            />
            <li>
              {{ t('management-review-guidelines.tip-two') }}
            </li>
            <q-btn
              :label="
                t('common.go-to-potential-public-interest-section')
              "
              icon="mdi-arrow-left"
              outline
              color="primary"
              class="q-my-md"
              :to="`/manuscript/${managementStep.data.manuscript_record_id}/form#pi`"
            />
          </ul>
          <QuestionEditor
            v-model="managementStep.data.comments"
            :title="t('reviewer-comments.title')"
          >
            <p>
              {{ t('reviewer-comments.description') }}
            </p>
          </QuestionEditor>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn
            color="primary"
            outline
            :label="t('review-step.save-comments')"
            @click="save"
          />
          <q-btn
            icon="mdi-arrow-decision"
            color="primary"
            :label="t('review-step.submit-decision')"
            @click="showDecisionDialog"
          />
          <SubmitDecisionDialog
            v-if="submitDecisionDialog"
            v-model="submitDecisionDialog"
            :management-review-step="managementStep.data"
            :manuscript-type="props.manuscriptRecord?.data.type || 'primary'"
            @decision="decisionSubmitted"
          />
        </q-card-actions>
      </q-card>
    </template>
    <template v-else>
      <q-card
        v-if="
          managementStep.data.status !== 'pending'
            && managementStep.data.status !== 'on_hold'
        "
        bordered
        flat
        class="bg-white q-pa-md"
      >
        <div
          v-if="managementStep.data.decision !== 'none'"
          class="q-mb-md"
        >
          <span
            class="text-weight-bold text-uppercase text-grey-8 q-mr-md"
          >{{ t('common.review-outcome') }}</span>
          <ManagementReviewStepDecisionSpan
            class="text-weight-bold text-uppercase text-primary"
            :class="`text-${color}`"
            :decision="managementStep.data.decision"
          />
        </div>
        <div v-if="managementStep.data.comments !== ''">
          <div
            class="text-weight-bold text-uppercase text-grey-8 q-mr-md"
          >
            {{ t('common.comments') }}
          </div>
          <SafeHtml class="text-grey-8" :html="managementStep.data.comments" />
        </div>
      </q-card>
    </template>
    <WarnOnUnsavedChanges :is-dirty="isDirty" />
  </q-timeline-entry>
</template>

<style scoped></style>
