<script setup lang="ts">
import { QBtn, QCardActions, QForm } from 'quasar'
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'
import BaseDialog from '@/components/BaseDialog.vue'
import DateInput from '@/components/DateInput.vue'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const now = new Date().toISOString().substring(0, 10)
const submittedOn = ref(now)

const loading = ref(false)

async function submit() {
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.submitted(
      props.manuscriptRecordId,
      submittedOn.value,
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
  <BaseDialog :title="$t('submitted-to-journal-dialog.title')">
    <div class="q-pa-md text-body1">
      <p>
        {{ $t('submitted-to-journal-dialog.text') }}
      </p>
      <QForm @submit="submit">
        <DateInput
          v-model="submittedOn"
          class="q-mx-sm"
          :label="$t('common.submitted-to-journal-on')"
          required
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
            :label="$t('common.update')"
            type="submit"
            :loading="loading"
          />
        </QCardActions>
      </QForm>
    </div>
  </BaseDialog>
</template>

<style scoped></style>
