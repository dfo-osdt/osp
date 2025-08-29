<script setup lang="ts">
import type { FundableType, FundingSourceResource } from '../FundingSource'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'
import { FundingSourceService } from '../FundingSource'
import CreateFundingSourceDialog from './CreateFundingSourceDialog.vue'
import FundingSourceList from './FundingSourceList.vue'

const props = defineProps<{
  fundableId: number
  fundableType: FundableType
  readonly?: boolean
}>()
const $q = useQuasar()
const { t } = useI18n()

const loading = ref(true)
const fundingSources = ref<FundingSourceResource[]>([])
const showCreateDialog = ref(false)

onMounted(async () => {
  await loadFundingSources()
})

async function loadFundingSources() {
  loading.value = true
  const fundingService = new FundingSourceService(props.fundableType)
  const response = await fundingService.all(props.fundableId)
  fundingSources.value = response.data
  loading.value = false
}

async function createFundingSource(fundingSource: FundingSourceResource) {
  fundingSources.value.push(fundingSource)
  $q.notify({
    message: t('common.created'),
    color: 'positive',
    icon: 'mdi-check-circle',
  })
  // close dialog
  showCreateDialog.value = false
}

async function editFundingSource(fundingSource: FundingSourceResource) {
  const index = fundingSources.value.findIndex(
    fs => fs.data.id === fundingSource.data.id,
  )
  fundingSources.value.splice(index, 1, fundingSource)
}

async function deleteFundingSource(fundingSource: FundingSourceResource) {
  const fundingService = new FundingSourceService(props.fundableType)
  try {
    await fundingService.delete(fundingSource.data)
    loadFundingSources()
    $q.notify({
      message: t('common.deleted'),
      color: 'positive',
      icon: 'mdi-check-circle',
    })
  }
  catch (err) {
    console.error(err)
  }
}
</script>

<template>
  <ContentCard class="q-mb-lg" secondary>
    <template #title>
      {{ $t('common.funding-sources') }}
    </template>
    <template #title-right>
      <FormSectionStatusIcon status="complete" />
    </template>
    <p>
      {{ $t('manage-funding-source-card.subtitle') }}
    </p>
    <FundingSourceList
      :funding-sources="fundingSources"
      :fundable-type="fundableType"
      :readonly="readonly"
      @edited-funding-source="editFundingSource"
      @delete-funding-source="deleteFundingSource"
    />
    <q-card-actions class="justify-end q-px-none q-pt-md">
      <q-btn
        v-if="!readonly"
        icon="mdi-plus"
        :label="$t('manage-funding-source-card.add-funding-source')"
        color="primary"
        outline
        @click="showCreateDialog = true"
      />
    </q-card-actions>
    <CreateFundingSourceDialog
      v-if="showCreateDialog"
      v-model="showCreateDialog"
      :fundable-type="fundableType"
      :fundable-id="fundableId"
      @create-funding-source="createFundingSource"
    />
  </ContentCard>
</template>

<style scoped></style>
