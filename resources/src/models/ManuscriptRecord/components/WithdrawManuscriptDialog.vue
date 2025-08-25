<script setup lang="ts">
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import { QBtn, QCardActions, QForm } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  (event: 'updated', arg: ManuscriptRecordResource): void
}>()

const loading = ref(false)
const withdrawReason = ref<string>('')

async function submit() {
  loading.value = true
  try {
    const resource = await ManuscriptRecordService.withdraw(
      props.manuscriptRecordId,
      withdrawReason.value,
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
        <QInput
          v-model="withdrawReason"
          :label="$t('withdraw-manuscript-dialog.reason')"
          outlined
          :rules="[(val) => !!val || $t('common.required')]"
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
