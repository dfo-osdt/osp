<script setup lang="ts">
import type {
  ManuscriptRecord,
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import { QBtn, QCardActions, QFile, QForm } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import CatalogueNumberInput from '@/components/CatalogueNumberInput.vue'
import IsbnInput from '@/components/IsbnInput.vue'
import IssueNumberInput from '@/components/IssueNumberInput.vue'
import JournalSelect from '@/models/Journal/components/JournalSelect.vue'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<{
  manuscriptRecord: ManuscriptRecord
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

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
  <BaseDialog :title="$t('submit-to-pub-team-dialog.title')">
    <div class="q-pa-md q">
      <p class="q-mx-sm">
        {{ $t('submit-to-pub-team-dialog.text') }}
      </p>
      <QForm class="q-gutter-y-md q-mt-md" @submit="submit">
        <JournalSelect
          v-model="journalId"
          class="q-mx-sm"
          :label="$t('common.journal')"
          :dfo-series-only="true"
          :rules="[
            (val: number | null) => val !== null || $t('common.required'),
          ]"
        />
        <IsbnInput v-model="isbn" class="q-mx-sm" />
        <CatalogueNumberInput v-model="catalogueNumber" required class="q-mx-sm" />
        <IssueNumberInput v-model="issueNumber" class="q-mx-sm" />
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
            <q-icon name="mdi-file-word-outline" />
          </template>
        </QFile>
        <QCardActions align="right">
          <QBtn
            v-close-popup
            :label="$t('common.cancel')"
            color="secondary"
            outline
          />
          <QBtn
            color="primary"
            :label="$t('common.submit')"
            type="submit"
            :loading="loading"
          />
        </QCardActions>
      </QForm>
    </div>
  </BaseDialog>
</template>

<style scoped></style>
