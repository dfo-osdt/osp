<script setup lang="ts">
import type { Ref } from 'vue'
import type {
  ManuscriptRecord,
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import { QFile, QForm, QStepper } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import CatalogueNumberInput from '@/components/CatalogueNumberInput.vue'
import IsbnInput from '@/components/IsbnInput.vue'
import IssueNumberInput from '@/components/IssueNumberInput.vue'
import JournalSelect from '@/models/Journal/components/JournalSelect.vue'
import { ManuscriptRecordService } from '../ManuscriptRecord'
import YesNoBooleanOptionGroup from './YesNoBooleanOptionGroup.vue'

const props = defineProps<{
  manuscriptRecord: ManuscriptRecord
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const form: Ref<QForm | null> = ref(null)
const stepper: Ref<QStepper | null> = ref(null)
const step = ref(1)
const validationError = ref(false)

const followedChecklist = ref(false)

const checklistLinks = useFormattingChecklistLinks()

const now = new Date().toISOString().substring(0, 10)
const submittedOn = ref(now)
const acceptedOn = ref(now)
const journalId = ref<number | null>(null)
const submissionFile = ref<File | null>(null)
const isbn = ref<string>('')
const catalogueNumber = ref<string>('')
const issueNumber = ref<string>('')

const maxFileSizeMB = import.meta.env.VITE_MAX_UPLOAD_SIZE_MB || 4
const { onFileRejected } = useFileRejectionHandler()

const loading = ref(false)

const isSecondaryManuscript = computed(
  () => props.manuscriptRecord.type === 'secondary',
)

async function submit() {
  if (!(await form.value?.validate())) {
    return
  }
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.accepted(
      props.manuscriptRecord.id,
      submittedOn.value,
      acceptedOn.value,
      journalId.value!,
      submissionFile.value,
      isbn.value,
      catalogueNumber.value,
      issueNumber.value,
    )
    emit('updated', resource)
  }
  catch (error) {
    console.error(error)
  }
  finally {
    loading.value = false
  }
}
</script>

<template>
  <BaseDialog :title="$t('submit-to-pub-team-dialog.title')" persistent>
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
      >
        <q-separator />
        <q-step
          :name="1"
          :title="$t('submit-to-pub-team-dialog.step1.title')"
          icon="mdi-clipboard-check-outline"
          :done="step > 1"
        >
          <div class="q-mx-md text-body1 text-primary text-weight-medium">
            {{ $t('submit-to-pub-team-dialog.step1.subtitle') }}
          </div>
          <p class="q-ma-md">
            {{ $t('submit-to-pub-team-dialog.step1.explanation') }}
          </p>
          <p v-if="checklistLinks.length > 0" class="q-ma-md">
            {{ $t('submit-to-pub-team-dialog.step1.view-checklist') }}
            <template v-for="(link, index) in checklistLinks" :key="link.url">
              <template v-if="index > 0">
                |
              </template>
              <a :href="link.url" target="_blank">{{ link.label }}</a>
            </template>
          </p>
          <p class="q-ma-md">
            {{ $t('submit-to-pub-team-dialog.step1.confirmation-text') }}
          </p>
          <YesNoBooleanOptionGroup
            v-model="followedChecklist"
            class="q-mr-lg"
            align="right"
          />
        </q-step>
        <q-step
          :name="2"
          :title="$t('submit-to-pub-team-dialog.step2.title')"
          icon="mdi-send"
          :error="validationError"
        >
          <p class="q-mx-sm">
            {{ $t('submit-to-pub-team-dialog.text') }}
          </p>
          <div class="q-gutter-y-md q-mt-md">
            <JournalSelect
              v-model="journalId"
              class="q-mx-sm"
              :label="$t('common.journal')"
              :dfo-series-only="true"
              :rules="[
                (val: number | null) => val !== null || $t('common.required'),
              ]"
            />
            <q-card flat bordered class="q-mx-sm q-pa-sm">
              <div class="text-subtitle2">
                {{ $t('submit-to-pub-team-dialog.publication-identifiers') }}
              </div>
              <p class="text-caption text-grey-8 q-mb-md">
                {{ $t('submit-to-pub-team-dialog.publication-identifiers-hint') }}
              </p>
              <div class="q-gutter-y-md">
                <CatalogueNumberInput v-model="catalogueNumber" required />
                <IsbnInput v-model="isbn" />
                <IssueNumberInput v-model="issueNumber" />
              </div>
            </q-card>
            <p class="text-caption text-grey-8 q-mx-sm q-mb-none">
              {{ $t('submit-to-pub-team-dialog.file-preference') }}
            </p>
            <QFile
              v-model="submissionFile"
              class="q-mx-sm"
              outlined
              use-chips
              counter
              :label="$t('submit-to-pub-team-dialog.file-label')"
              :hint="
                $t('submit-to-pub-team-dialog.file-hint', { max: maxFileSizeMB })
              "
              accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
              :max-file-size="maxFileSizeMB * 1e6"
              :rules="[
                (val: File | null) =>
                  !isSecondaryManuscript || val !== null || $t('common.required'),
              ]"
              @rejected="onFileRejected"
            >
              <template #prepend>
                <q-icon name="mdi-file-document-outline" />
              </template>
            </QFile>
          </div>
        </q-step>
        <template #navigation>
          <q-stepper-navigation class="flex justify-end">
            <q-btn
              color="primary"
              :label="step === 2 ? $t('common.submit') : $t('common.next')"
              :disable="!followedChecklist || loading"
              :loading="loading"
              @click="step === 2 ? submit() : stepper?.next()"
            />
            <q-btn
              v-close-popup
              flat
              :label="$t('common.cancel')"
            />
          </q-stepper-navigation>
        </template>
      </QStepper>
    </QForm>
  </BaseDialog>
</template>

<style scoped></style>
