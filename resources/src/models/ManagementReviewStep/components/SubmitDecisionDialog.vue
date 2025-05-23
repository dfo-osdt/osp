<script setup lang="ts">
import type { ManuscriptRecordType } from '@/models/ManuscriptRecord/ManuscriptRecord'
import type { QDialog } from 'quasar'
import type { Ref } from 'vue'
import type {
  ManagementReviewStep,
  ManagementReviewStepResource,
} from '../ManagementReviewStep'
import BaseDialog from '@/components/BaseDialog.vue'
import { ManuscriptAuthorService } from '@/models/ManuscriptAuthor/ManuscriptAuthor'
import { ManuscriptRecordService } from '@/models/ManuscriptRecord/ManuscriptRecord'
import UserSelect from '@/models/User/components/UserSelect.vue'
import { QForm, QStepper } from 'quasar'
import {
  ManagementReviewStepService,
} from '../ManagementReviewStep'

const props = defineProps<{
  managementReviewStep: ManagementReviewStep
  manuscriptType: ManuscriptRecordType
}>()
const emit = defineEmits<{
  (event: 'decision', arg: ManagementReviewStepResource): void
}>()
const authStore = useAuthStore()
const { t } = useI18n()

const dialog: Ref<QDialog | null> = ref(null)
const form: Ref<QForm | null> = ref(null)
const stepper: Ref<QStepper | null> = ref(null)
const step = ref(1)
const validationError = ref(false)

type Decision =
  | 'complete'
  | 'refer'
  | 'referWithRevision'
  | 'revision'
  | 'reassign'

interface DecisionOption {
  label: string
  value: Decision
  description: string
  disabled: boolean
}

const canComplete = computed(() => {
  return props.managementReviewStep.can_complete
})

const decision: Ref<Decision> = canComplete.value
  ? ref('complete')
  : ref('refer')

const nextUserId = ref<number | null>(null)

/** Decision flow variables */
const agreeToTerms = ref(false)
const agreeToTermsOptions = ref([
  {
    label: t('common.yes'),
    value: true,
  },
  {
    label: t('common.no'),
    value: false,
  },
])

const nextReviewerStepDisabled = computed(() => {
  return (
    decision.value === 'complete'
    || decision.value === 'revision'
  )
})

const nextDisabled = computed(() => {
  if (step.value === 1) {
    return false
  }
  else if (step.value === 2) {
    return nextUserId.value === null
  }
  else if (step.value === 3) {
    return !agreeToTerms.value
  }
  return true
})

/**
 * This is used to determine which options are available to the user.
 */
const stepHasNoComments = computed(() => {
  return props.managementReviewStep.comments.length === 0
})

/**
 * Checks that the user has permission to complete a secondary review.
 */
const userCanCompleteReview = computed(() => {
  if (props.manuscriptType === 'secondary') {
    return authStore.user?.can('complete_interntal_management_review') ?? false
  }
  return true
})

/**
 * The options available to the user for their decision.
 */
const options = ref<DecisionOption[]>([
  {
    label: t('decision.complete'),
    value: 'complete',
    description: t('decision.complete-desc'),
    disabled: !userCanCompleteReview.value,
  },
  {
    label: t('decision.revision'),
    value: 'revision',
    description: t('decision.revision-desc'),
    disabled: stepHasNoComments.value,
  },
  {
    label: t('decision.refer'),
    value: 'refer',
    description: t('decision.refer-desc'),
    disabled: stepHasNoComments.value,
  },
  {
    label: t('decision.refer-revision'),
    value: 'referWithRevision',
    description: t('decision.revision-revision-desc'),
    disabled: stepHasNoComments.value,
  },
  {
    label: t('decision.reassign'),
    value: 'reassign',
    description: t('decision.reassign-desc'),
    disabled: stepHasNoComments.value,
  },
])

/**
 * Variables used to ensure that the next reviewer is not the author or the
 * owner of the manuscript.
 */
const authorEmails = ref<string[]>([])
const ownerId = ref<number>(0)

onMounted(async () => {
  authorEmails.value = await getAllManuscriptAuthorsEmails()
  ownerId.value = await getManuscriptOwnerId()
})

async function getAllManuscriptAuthorsEmails(): Promise<string[]> {
  const manuscriptAuthors = await ManuscriptAuthorService.list(
    props.managementReviewStep.manuscript_record_id,
  )
  return manuscriptAuthors.data.map(
    manuscriptAuthor => manuscriptAuthor.data.author?.data.email ?? '',
  )
}

async function getManuscriptOwnerId(): Promise<number> {
  const mansuscriptRecord = await ManuscriptRecordService.find(
    props.managementReviewStep.manuscript_record_id,
  )
  return mansuscriptRecord.data.user_id
}

/**
 * Submits the decision to the API.
 */
const loading = ref(false)
async function submit() {
  let response: ManagementReviewStepResource | null = null
  switch (decision.value) {
    case 'complete':
      response = await ManagementReviewStepService.complete(
        props.managementReviewStep,
      )
      break
    case 'referWithRevision':
      if (nextUserId.value === null) {
        throw new Error('nextUserId is null')
      }
      response = await ManagementReviewStepService.refer(
        props.managementReviewStep,
        nextUserId.value,
        true,
      )
      break
    case 'refer':
      if (nextUserId.value === null) {
        throw new Error('nextUserId is null')
      }
      response = await ManagementReviewStepService.refer(
        props.managementReviewStep,
        nextUserId.value,
      )
      break
    case 'reassign':
      if (nextUserId.value === null) {
        throw new Error('nextUserId is null')
      }
      response = await ManagementReviewStepService.reassign(
        props.managementReviewStep,
        nextUserId.value,
      )
      break
    case 'revision':
      response = await ManagementReviewStepService.revision(
        props.managementReviewStep,
      )
      break
  }
  if (response === null)
    return
  emit('decision', response)
}
</script>

<template>
  <BaseDialog
    ref="dialog"
    :title="$t('review-step.submit-decision')"
    persistent
  >
    <QForm
      ref="form"
      @validation-error="validationError = true"
      @validation-success="validationError = false"
    >
      <QStepper
        ref="stepper"
        v-model="step"
        color="primary"
        animated
        flat
        bordered
        keep-alive
      >
        <q-separator />
        <q-step
          :name="1"
          :title="$t('submit-decision-dialog.your-decision')"
          icon="mdi-check"
          :done="step > 1"
        >
          <div
            class="q-mx-md text-body1 text-primary text-weight-medium"
          >
            {{ $t('submit-decision-dialog.select-decision') }}
          </div>
          <q-list>
            <q-item
              v-for="option in options"
              :key="option.value"
              v-ripple
              tag="label"
              :disable="option.disabled"
            >
              <q-item-section avatar>
                <q-radio
                  v-model="decision"
                  :val="option.value"
                  :disable="option.disabled"
                />
              </q-item-section>
              <q-item-section>
                <q-item-label class="text-body1">
                  {{
                    option.label
                  }}
                </q-item-label>
                <q-item-label class="text-body2 text-grey-8">
                  {{ option.description }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-step>
        <q-step
          :name="2"
          :title="$t('submit-decision-dialog.select-next-reviewer')"
          icon="mdi-account-search"
          :error="validationError"
          style="min-height: 275px"
          :disable="nextReviewerStepDisabled"
          :done="step > 2"
        >
          <div
            class="q-mx-md text-body1 text-primary text-weight-medium"
          >
            {{ $t('submit-decision-dialog.next-reviewer') }}
          </div>
          <p class="q-ma-md">
            {{ $t('submit-decision-dialog.next-reviewer-hint') }}
          </p>
          <UserSelect
            v-model="nextUserId"
            :label="$t('submit-decision-dialog.next-reviewer')"
            class="q-ma-md"
            :disabled-emails="authorEmails"
            :disabled-ids="[ownerId, managementReviewStep.user_id]"
            :rules="[
              (val: number | null) =>
                val !== null
                || $t(
                  'submit-decision-dialog.subsequent-reviewer-is-required',
                ),
            ]"
          />
        </q-step>
        <q-step
          :name="3"
          :title="$t('submit-decision-dialog.confirm-decision')"
          icon="mdi-check-decagram"
          :done="step > 3"
        >
          <p class="q-ma-md">
            {{ $t('submit-decision-dialog.certify-text') }}
          </p>
          <q-option-group
            v-model="agreeToTerms"
            class="q-mr-lg"
            align="right"
            :options="agreeToTermsOptions"
            inline
          />
        </q-step>
        <template #navigation>
          <q-stepper-navigation class="flex justify-end">
            <q-btn
              v-if="step > 1"
              color="primary"
              :label="$t('common.back')"
              class="q-mr-sm"
              outline
              @click="stepper?.previous()"
            />
            <q-btn
              color="primary"
              :label="
                step === 3
                  ? $t('common.submit')
                  : $t('common.next')
              "
              :loading="loading"
              :disable="nextDisabled"
              @click="step === 3 ? submit() : stepper?.next()"
            />
            <q-btn
              v-close-popup
              flat
              :label="$t('common.cancel')"
            />
          </q-stepper-navigation>
        </template>
        <template #message>
          <q-banner
            v-if="step === 1 && stepHasNoComments"
            class="bg-primary text-white"
          >
            <template #avatar>
              <q-icon
                name="mdi-comment-alert-outline"
                size="md"
              />
            </template>
            <span v-if="!canComplete">{{ $t('submit-decision-dialog.no-comments-warning-cant-complete') }}</span>
            <span v-else>{{ $t('submit-decision-dialog.no-comments-warning') }}</span>
          </q-banner>
        </template>
      </QStepper>
    </QForm>
  </BaseDialog>
</template>

<style scoped></style>
