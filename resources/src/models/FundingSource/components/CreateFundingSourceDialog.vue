<script setup lang="ts">
import type {
  FundableType,
  FundingSourceResource,
} from '../FundingSource'
import type { Funder } from '@/models/Funder/Funder'
import BaseDialog from '@/components/BaseDialog.vue'
import {
  FundingSourceService,
} from '../FundingSource'

const props = defineProps<{
  fundableId: number
  fundableType: FundableType
}>()
const emit = defineEmits<{
  (
    event: 'create:funding-source',
    fundingSource: FundingSourceResource
  ): void
}>()
const { t } = useI18n()
const funderStore = useFunderStore()
funderStore.getFunders()

// form data
const title = ref('')
const description = ref('')
const funder = ref<Funder | null>(null)

async function createFundingSource() {
  if (!funder.value)
    return // will have been caught by validation
  const fundingService = new FundingSourceService(props.fundableType)
  const response = await fundingService.create(props.fundableId, {
    title: title.value,
    description: description.value,
    funder_id: funder.value.id,
  })
  emit('create:funding-source', response)
}
</script>

<template>
  <BaseDialog :title="$t('funding-source.create-funding-source')">
    <q-form @submit="createFundingSource">
      <q-card-section>
        <q-select
          v-model="funder"
          :label="$t('common.funder')"
          :options="funderStore.funders"
          option-value="id"
          :option-label="(funder: Funder) => $i18n.locale === 'fr' ? funder.name_fr : funder.name_en"
          outlined
          :rules="[(val) => !!val || t('common.required')]"
          class="q-mb-md"
        />
        <q-input
          v-model="title"
          :label="$t('common.title')"
          outlined
          :rules="[
            (val) => !!val || t('common.required'),
            (val) =>
              val.length <= 255
              || t(
                'common.validation.must-be-less-than-x-characters',
                [255],
              ),
          ]"
          class="q-mb-md"
        />
        <q-input
          v-model="description"
          type="textarea"
          :label="$t('common.description')"
          outlined
          :rules="[
            (val) =>
              val.length <= 1500
              || t(
                'common.validation.must-be-less-than-x-characters',
                [1500],
              ),
          ]"
          class="q-mb-md"
        />
      </q-card-section>
      <q-card-actions class="justify-end">
        <q-btn
          v-close-popup
          :label="$t('common.cancel')"
          color="primary"
          outline
        />
        <q-btn
          :label="$t('common.create')"
          type="submit"
          color="primary"
        />
      </q-card-actions>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
