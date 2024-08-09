<script setup lang="ts">
import { QBtn, QCardActions, QForm } from 'quasar'
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'
import BaseDialog from '@/components/BaseDialog.vue'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const loading = ref(false)

async function submit() {
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.withdraw(
      props.manuscriptRecordId,
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
  <BaseDialog :title="$t('withdraw-manuscript-dialog.title')">
    <div class="q-pa-md text-body1">
      <p>
        {{ $t('withdraw-manuscript-dialog.text') }}
      </p>
      <QForm @submit="submit">
        <QCardActions align="right">
          <QBtn
            v-close-popup
            :label="$t('common.cancel')"
            color="secondary"
            outline
          />
          <QBtn
            color="primary"
            :label="$t('common.withdraw')"
            type="submit"
            :loading="loading"
          />
        </QCardActions>
      </QForm>
    </div>
  </BaseDialog>
</template>

<style scoped></style>
