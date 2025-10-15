<script setup lang="ts">
import type { Ref } from 'vue'
import type {
  ManuscriptRecord,
  ManuscriptRecordSummary,
  ManuscriptRecordType,
} from '../ManuscriptRecord'
import { QStepper } from 'quasar'
import {
  ManuscriptRecordService,
} from '../ManuscriptRecord'
import ManuscriptTypeRadioSelection from './ManuscriptTypeRadioSelection.vue'

const props = defineProps<{
  sourceManuscript: ManuscriptRecord | ManuscriptRecordSummary
}>()

// router
const router = useRouter()

// stepper
const stepper: Ref<QStepper | null> = ref(null)
const step = ref(1)

async function next() {
  if (step.value === 2) {
    // clone manuscript
    clone()
    return
  }
  stepper.value?.next()
}

// information required to clone a manuscript record
const type: Ref<ManuscriptRecordType> = ref(props.sourceManuscript.type)
const loading = ref(false)

async function clone() {
  loading.value = true
  try {
    // clone the manuscript record
    const response = await ManuscriptRecordService.clone(props.sourceManuscript.id, type.value)

    router.push({
      name: 'manuscript',
      params: { id: response.data.id },
    })
  }
  catch (error) {
    console.error(error)
  }
}
</script>

<template>
  <q-dialog persistent>
    <q-card style="width: 700px; max-width: 80vw" outlined>
      <q-card-section class="bg-teal-1 text-h6 text-primary">
        {{ $t('clone-manuscript-record-dialog.title') }}
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
              {{ $t('clone-manuscript-record-dialog.step1.text') }}
            </div>
            <ManuscriptTypeRadioSelection v-model="type" />
          </q-step>
          <q-step
            :name="2"
            :title="$t('common.clone')"
            icon="mdi-content-copy"
            :done="step > 2"
            style="min-height: 275px"
          >
            <div class="q-pa-md">
              {{
                $t(
                  'clone-manuscript-record-dialog.clone-text',
                  { title: props.sourceManuscript.title },
                )
              }}
            </div>
          </q-step>
          <template #navigation>
            <q-stepper-navigation class="flex justify-end">
              <q-btn
                color="primary"
                :label="
                  step === 2
                    ? $t('common.clone')
                    : $t('common.continue')
                "
                class="q-mr-sm"
                :loading="loading"
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
