<script setup lang="ts">
import type {
  ManuscriptRecord,
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import { QBtn, QCardActions, QForm } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import JournalSelect from '@/models/Journal/components/JournalSelect.vue'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'

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

const loading = ref(false)

async function submit() {
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.accepted(
      props.manuscriptRecord.id,
      submittedOn.value,
      acceptedOn.value,
      journalId.value!, // should not be null because of validation
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
      <p>
        {{ $t('submit-to-pub-team-dialog.text') }}
      </p>
      <QForm class="q-gutter-y-md q-mt-md" @submit="submit">
        <JournalSelect
          v-model="journalId"
          class="q-mx-sm"
          :label="$t('common.journal')"
          :dfo-series-only="true"
          :rules="[(val: number|null) => val !== null || $t('common.required')]"
        />
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
