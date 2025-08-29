<script setup lang="ts">
import type { FundableType, FundingSourceResource } from '../FundingSource'
import FundingSourceListItem from './FundingSourceListItem.vue'

defineProps<{
  fundableType: FundableType
  fundingSources: FundingSourceResource[]
  readonly?: boolean
}>()

const emit = defineEmits<{
  (event: 'editedFundingSource', fundingSource: FundingSourceResource): void
  (event: 'deleteFundingSource', fundingSource: FundingSourceResource): void
}>()

function editedFundingSource(fundingSource: FundingSourceResource) {
  emit('editedFundingSource', fundingSource)
}

function deleteFundingSource(fundingSource: FundingSourceResource) {
  emit('deleteFundingSource', fundingSource)
}
</script>

<template>
  <q-list v-if="fundingSources.length !== 0" bordered separator>
    <FundingSourceListItem
      v-for="fundingSource in fundingSources"
      :key="fundingSource.data.id"
      :funding-source="fundingSource"
      :fundable-type="fundableType"
      :readonly="readonly"
      @edited-funding-source="editedFundingSource"
      @delete-funding-source="deleteFundingSource"
    />
  </q-list>
  <div v-else>
    <q-list bordered>
      <q-item>
        <q-item-section avatar>
          <q-icon color="primary" name="mdi-currency-usd" />
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <span class="text-grey-8">{{
              $t('funding-source-list.no-funding-source-given')
            }}</span>
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<style scoped></style>
