<script setup lang="ts">
import type {
  ManagementReviewStepResource,
} from '../ManagementReviewStep'
import { QBtn, QCardActions, QForm } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import {
  ManagementReviewStepService,
} from '../ManagementReviewStep'

const props = defineProps<{
  managementReviewStep: ManagementReviewStepResource
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManagementReviewStepResource): void
}>()

const loading = ref(false)

async function submit() {
  loading.value = true
  try {
    const resource = await ManagementReviewStepService.withdraw(
      props.managementReviewStep.data,
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
