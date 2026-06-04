<script setup lang="ts">
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import { QBtn, QCardActions, QForm, QInput, useQuasar } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const showDialog = defineModel<boolean>({ required: true })

const { t } = useI18n()
const $q = useQuasar()

const loading = ref(false)
const reason = ref('')

const isReasonValid = computed(() => {
  return reason.value.trim().length > 2
})

async function submit() {
  if (!isReasonValid.value) {
    return
  }

  loading.value = true

  try {
    const manuscriptRecord = await ManuscriptRecordService.unsubmitForReview(
      props.manuscriptRecordId,
      reason.value,
    )
    emit('updated', manuscriptRecord)
  }
  catch {
    $q.notify({
      message: t('manuscript-progress-view.unsubmit-failed'),
      color: 'negative',
      icon: 'mdi-alert-circle-outline',
    })
  }
  finally {
    loading.value = false
  }
}
</script>

<template>
  <BaseDialog v-model="showDialog" persistent :title="$t('manuscript-progress-view.unsubmit-title')">
    <div class="q-pa-md text-body1">
      <p>
        {{ $t('manuscript-progress-view.unsubmit-details') }}
      </p>
      <QForm @submit="submit">
        <QInput
          v-model="reason"
          :label="$t('manuscript-progress-view.unsubmit-reason-label')"
          outlined
          type="textarea"
          autogrow
          :rules="[(val: string) => val.trim().length > 2 || $t('common.required')]"
        />
        <QCardActions align="right">
          <QBtn
            v-close-popup
            :label="$t('common.cancel')"
            color="secondary"
            outline
          />
          <QBtn
            color="negative"
            :label="$t('common.confirm')"
            type="submit"
            :disable="!isReasonValid"
            :loading="loading"
          />
        </QCardActions>
      </QForm>
    </div>
  </BaseDialog>
</template>

<style scoped></style>
