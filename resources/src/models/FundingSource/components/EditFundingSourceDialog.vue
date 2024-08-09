<script setup lang="ts">
import type {
  FundingSource,
  FundingSourceResource,
} from '../FundingSource'
import {
  FundingSourceService,
} from '../FundingSource'
import BaseDialog from '@/components/BaseDialog.vue'
import type { Funder } from '@/models/Funder/Funder'

const props = defineProps<{
  fundingSource: FundingSource
}>()
const emit = defineEmits<{
  (
    event: 'edited:funding-source',
    fundingSource: FundingSourceResource
  ): void
}>()
const funderStore = useFunderStore()
funderStore.getFunders()
const funders = computed(() => funderStore.funders)

// form data
const title = ref(props.fundingSource.title)
const description = ref(props.fundingSource.description ?? '')
const funder = ref<Funder | null>(props.fundingSource.funder?.data ?? null)

async function editFundingSource() {
  if (!funder.value)
    return // will have been caught by validation
  const fundingService = new FundingSourceService(
    props.fundingSource.fundable_type,
  )
  const response = await fundingService.update({
    id: props.fundingSource.id,
    title: title.value,
    description: description.value,
    funder_id: funder.value.id,
    fundable_type: props.fundingSource.fundable_type,
    fundable_id: props.fundingSource.fundable_id,
  })
  emit('edited:funding-source', response)
}
</script>

<template>
  <BaseDialog :title="$t('funding-source.edit-funding-source')">
    <q-form @submit="editFundingSource">
      <q-card-section>
        <q-select
          v-model="funder"
          :label="$t('common.funder')"
          :options="funders"
          option-value="id"
          option-label="name_en"
          outlined
          :rules="[(val) => !!val || $t('common.required')]"
          class="q-mb-md"
        />
        <q-input
          v-model="title"
          :label="$t('common.title')"
          outlined
          :rules="[
            (val) => !!val || $t('common.required'),
            (val) =>
              val.length <= 255
              || $t('common.validation.must-be-less-than-x-characters', [255]),
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
              || $t('common.validation.must-be-less-than-x-characters', [1500]),
          ]"
          class="q-mb-md"
        />
      </q-card-section>
      <q-card-actions class="justify-end">
        <q-btn v-close-popup :label="$t('common.cancel')" color="primary" outline />
        <q-btn :label="$t('common.save')" type="submit" color="primary" />
      </q-card-actions>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
