<template>
    <q-list v-if="fundingSources.length !== 0" bordered separator>
        <FundingSourceListItem
            v-for="fundingSource in fundingSources"
            :key="fundingSource.data.id"
            :funding-source="fundingSource"
            :fundable-type="fundableType"
            :readonly="readonly"
            @edited:funding-source="editedFundingSource"
            @delete:funding-source="deleteFundingSource"
        />
    </q-list>
    <div v-else>
        <q-list bordered>
            <q-item
                ><q-item-section avatar>
                    <q-icon color="primary" name="mdi-currency-usd" />
                </q-item-section>
                <q-item-section>
                    <q-item-label>
                        <span class="text-grey-8"
                            >No funding source given.</span
                        >
                    </q-item-label>
                </q-item-section>
            </q-item>
        </q-list>
    </div>
</template>

<script setup lang="ts">
import { FundableType, FundingSourceResource } from '../FundingSource';
import FundingSourceListItem from './FundingSourceListItem.vue';

defineProps<{
    fundableType: FundableType;
    fundingSources: FundingSourceResource[];
    readonly?: boolean;
}>();

const emit = defineEmits<{
    (
        event: 'edited:funding-source',
        fundingSource: FundingSourceResource
    ): void;
    (
        event: 'delete:funding-source',
        fundingSource: FundingSourceResource
    ): void;
}>();

function editedFundingSource(fundingSource: FundingSourceResource) {
    emit('edited:funding-source', fundingSource);
}

function deleteFundingSource(fundingSource: FundingSourceResource) {
    emit('delete:funding-source', fundingSource);
}
</script>

<style scoped></style>
