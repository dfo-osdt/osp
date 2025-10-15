<script setup lang="ts">
import type { Ref } from 'vue'
import type {
  BaseManuscriptRecord,
  ManuscriptRecordType,
} from '../ManuscriptRecord'
import { QForm, QStepper } from 'quasar'
import RegionSelect from '@/models/Region/components/RegionSelect.vue'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'
import ManuscriptTypeRadioSelection from './ManuscriptTypeRadioSelection.vue'

// router
const router = useRouter()

// stepper
const stepper: Ref<QStepper | null> = ref(null)
const step = ref(1)
const manuscriptDetailForm: Ref<QForm | null> = ref(null)
const manuscriptDetailFormValid = ref(true)
const createButtonDisabled = ref(false)

async function next() {
  if (step.value === 3) {
    // create manuscript
    createButtonDisabled.value = true
    create()
    return
  }
  if (step.value === 2) {
    const valid = await manuscriptDetailForm.value?.validate()
    manuscriptDetailFormValid.value = valid === undefined ? true : valid
    if (!manuscriptDetailFormValid.value)
      return
  }
  stepper.value?.next()
}

// information required to create a manuscript record
const type: Ref<ManuscriptRecordType> = ref('primary')
const title = ref('')
const regionId: Ref<number | null> = ref(null)

async function create() {
  const record: BaseManuscriptRecord = {
    type: type.value,
    title: title.value,
    region_id: regionId.value ?? 1,
  }

  // create the manuscript record
  await ManuscriptRecordService.create(record)
    .then((response) => {
      router.push({
        name: 'manuscript',
        params: { id: response.data.id },
      })
    })
    .catch((error) => {
      console.error(error)
    })
}
</script>

<template>
  <q-dialog persistent>
    <q-card style="width: 700px; max-width: 80vw" outlined>
      <q-card-section class="bg-teal-1 text-h6 text-primary">
        {{ $t('create-manuscript-record-dialog.title') }}
      </q-card-section>
      <q-separator />
      <q-card-section class="bg-grey-1 q-pa-none text-body1">
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
            :title="$t('common.type-of-publication')"
            icon="mdi-call-split"
            :done="step > 1"
            style="min-height: 275px"
          >
            <div class="q-mb-md">
              {{ $t('create-manuscript-record-dialog.step1.text') }}
            </div>
            <ManuscriptTypeRadioSelection v-model="type" />
          </q-step>
          <q-step
            :name="2"
            :title="
              $t('create-manuscript-record-dialog.step2.title')
            "
            icon="mdi-file-document-edit-outline"
            :done="step > 2"
            :error="!manuscriptDetailFormValid"
            style="min-height: 275px"
          >
            <QForm ref="manuscriptDetailForm">
              <div class="q-mb-md">
                {{
                  $t(
                    'create-manuscript-record-dialog.what-is-the-working-title-of-your-manuscript',
                  )
                }}
              </div>
              <q-input
                v-model="title"
                outlined
                :label="$t('common.title')"
                :placeholder="
                  $t(
                    'create-manuscript-record-dialog.enter-the-title-of-your-manuscript',
                  )
                "
                :rules="[
                  (val) =>
                    val.length > 0 || $t('common.required'),
                ]"
              />
              <div class="q-my-md">
                {{
                  $t(
                    'create-manuscript-record-dialog.select-region-text',
                  )
                }}
              </div>
              <RegionSelect
                v-model="regionId"
                outlined
                :placeholder="
                  $t(
                    'manuscript.please-select-the-lead-region',
                  )
                "
                :rules="[
                  (val: number) =>
                    val !== null || $t('common.required'),
                ]"
              />
            </QForm>
          </q-step>
          <q-step
            :name="3"
            :title="$t('common.create')"
            icon="mdi-file-document-check"
            :done="step > 3"
            style="min-height: 275px"
          >
            <div class="q-pa-md">
              {{
                $t(
                  'create-manuscript-record-dialog.create-text',
                )
              }}
            </div>
          </q-step>
          <template #navigation>
            <q-stepper-navigation class="flex justify-end">
              <q-btn
                color="primary"
                :label="
                  step === 3
                    ? $t('common.create')
                    : $t('common.continue')
                "
                class="q-mr-sm"
                :loading="createButtonDisabled"
                @click="next()"
              />
              <q-btn
                v-if="step > 1"
                flat
                color="primary"
                :label="$t('common.back')"
                @click="stepper?.previous()"
              />
              <q-btn
                v-close-popup
                flat
                :label="$t('common.cancel')"
              />
            </q-stepper-navigation>
          </template>
        </QStepper>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<style scoped></style>
