<template>
    <q-list v-if="fundingSources.length !== 0" bordered separator>
        <FundingSourceListItem
            v-for="fundingSource in fundingSources"
            :key="fundingSource.data.id"
            :funding-source="fundingSource"
            :readonly="readonly"
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
import { FundingSourceResource } from '../FundingSource';
import FundingSourceListItem from './FundingSourceListItem.vue';

const props = defineProps<{
    fundingSources: FundingSourceResource[];
    readonly?: boolean;
}>();
</script>

<style scoped></style>
