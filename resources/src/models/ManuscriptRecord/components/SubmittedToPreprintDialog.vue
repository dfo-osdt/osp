<script setup lang="ts">
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import BaseDialog from '@/components/BaseDialog.vue'
import DateInput from '@/components/DateInput.vue'
import UrlInput from '@/components/UrlInput.vue'
import { QBtn, QCardActions, QForm } from 'quasar'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const now = new Date().toISOString().substring(0, 10)
const submittedOn = ref(now)
const url = ref('')

const loading = ref(false)

async function submit() {
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.submittedToPreprint(
      props.manuscriptRecordId,
      submittedOn.value,
      url.value,
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
  <BaseDialog :title="$t('submitted-to-preprint-dialog.title')">
    <div class="q-pa-md text-body1">
      <p>
        {{ $t('submitted-to-preprint-dialog.text') }}
      </p>
      <QForm @submit="submit">
        <DateInput
          v-model="submittedOn"
          class="q-mx-sm"
          :label="$t('common.submitted-to-preprint-on')"
          required
        />
        <p class="q-mt-md q-ml-sm text-body2">
          {{ $t('common.preprint-url-hint') }}
        </p>
        <UrlInput
          v-model="url"
          required
          class="q-mt-sm"
          :label="$t('common.preprint-url')"
          :placeholder="$t('common.preprint-url-placeholder')"
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
