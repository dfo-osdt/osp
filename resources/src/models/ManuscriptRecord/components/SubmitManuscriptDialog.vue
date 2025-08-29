<script setup lang="ts">
import type { QDialog } from 'quasar'
import type { Ref } from 'vue'
import type {
  ManuscriptRecordResource,
} from '../ManuscriptRecord'
import type { ManuscriptAuthorResource } from '@/models/ManuscriptAuthor/ManuscriptAuthor'
import { QForm, QStepper } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import UserSelect from '@/models/User/components/UserSelect.vue'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'

const { manuscriptRecord, manuscriptAuthors = [] } = defineProps<{
  manuscriptRecord: ManuscriptRecordResource
  manuscriptAuthors?: ManuscriptAuthorResource[]
}>()

const emit = defineEmits<{
  (event: 'submitted', arg: ManuscriptRecordResource): void
}>()

const { t } = useI18n()

const dialog: Ref<QDialog | null> = ref(null)
const form: Ref<QForm | null> = ref(null)
const stepper: Ref<QStepper | null> = ref(null)
const step = ref(1)
const validationError = ref(false)

const divisionManagerUserId = ref<number | null>(null)
const agreeToTerms = ref(false)
const agreeToTermsOptions = ref([
  {
    label: t('common.yes'),
    value: true,
  },
  {
    label: t('common.no'),
    value: false,
  },
])

const authorEmails = computed(() => {
  return manuscriptAuthors.map(
    author => author.data.author?.data.email ?? '',
  )
})

const loading = ref(false)
async function submit() {
  if (await form.value?.validate()) {
    loading.value = true

    if (divisionManagerUserId?.value === null)
      return

    const response = await ManuscriptRecordService.submitForReview(
      manuscriptRecord.data.id,
      divisionManagerUserId.value,
    )

    loading.value = false
    emit('submitted', response)
  }
}
</script>

<template>
  <BaseDialog
    ref="dialog"
    :title="$t('submit-manuscript-dialog.title')"
    persistent
  >
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
          :title="$t('submit-manuscript-dialog.step1.title')"
          icon="mdi-check"
          :done="step > 1"
        >
          <div
            class="q-mx-md text-body1 text-primary text-weight-medium"
          >
            {{ $t("submit-manuscript-dialog.step1.subtitle") }}
          </div>
          <p class="q-ma-md">
            {{
              $t(
                "submit-manuscript-dialog.step1.confirmation-text",
              )
            }}
          </p>
          <q-option-group
            v-model="agreeToTerms"
            class="q-mr-lg"
            align="right"
            :options="agreeToTermsOptions"
            inline
          />
        </q-step>
        <q-step
          :name="2"
          :title="$t('submit-manuscript-dialog.step2.title')"
          icon="mdi-account-search"
          :error="validationError"
          style="min-height: 275px"
        >
          <div
            class="q-mx-md text-body1 text-primary text-weight-medium"
          >
            {{ $t("submit-manuscript-dialog.step2.subtitle") }}
          </div>
          <p class="q-ma-md">
            {{ $t("submit-manuscript-dialog.step2.p1") }}
          </p>
          <p class="q-ma-md">
            {{ $t("submit-manuscript-dialog.step2.p2") }}
          </p>
          <UserSelect
            v-model="divisionManagerUserId"
            :label="$t('common.division-manager')"
            class="q-ma-md"
            :disabled-emails="authorEmails"
            :disabled-ids="[manuscriptRecord.data.user_id]"
            :rules="[(val: number|null) => val !== null || $t('common.required')]"
          />
        </q-step>
        <template #navigation>
          <q-stepper-navigation class="flex justify-end">
            <q-btn
              color="primary"
              :label="
                step === 2
                  ? $t('common.submit')
                  : $t('common.next')
              "
              :disable="!agreeToTerms"
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
