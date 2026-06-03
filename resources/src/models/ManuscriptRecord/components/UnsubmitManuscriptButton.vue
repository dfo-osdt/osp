<script
  setup
  lang="ts"
>
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  unsubmitted: [manuscriptRecord: ManuscriptRecordResource]
}>()

const { t } = useI18n()
const $q = useQuasar()

const loading = ref(false)

function confirmUnsubmitManuscript() {
  $q.dialog({
    title: t('manuscript-progress-view.unsubmit-title'),
    message: t('manuscript-progress-view.unsubmit-details'),
    prompt: {
      model: '',
      type: 'textarea',
      label: t('manuscript-progress-view.unsubmit-reason-label'),
      isValid: val => val.trim().length > 2,
    },
    cancel: true,
    persistent: false,
    ok: {
      label: t('common.confirm'),
      color: 'negative',
    },
  }).onOk((value: string) => {
    unsubmitManuscript(value)
  })
}

async function unsubmitManuscript(reason: string) {
  loading.value = true

  try {
    const manuscriptRecord = await ManuscriptRecordService.unsubmitForReview(
      props.manuscriptRecordId,
      reason,
    )
    emit('unsubmitted', manuscriptRecord)
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
  <q-btn
    color="negative"
    outline
    icon="mdi-undo"
    :label="$t('manuscript-progress-view.unsubmit')"
    :loading="loading"
    @click="confirmUnsubmitManuscript"
  />
</template>
