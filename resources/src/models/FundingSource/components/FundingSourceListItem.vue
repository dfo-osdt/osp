<template>
    <q-item>
        <q-item-section>
            <q-item-label overline>{{
                fundingSource.data.funder?.data.name_en
            }}</q-item-label>
            <q-item-label>{{ fundingSource.data.title }} </q-item-label>
            <q-item-label caption>{{
                fundingSource.data.description
            }}</q-item-label>
        </q-item-section>
        <q-item-section v-if="!readonly" side>
            <div class="text-grey-8 q-gutter-xs">
                <q-btn
                    class="gt-xs"
                    size="12px"
                    flat
                    dense
                    round
                    icon="mdi-pencil"
                    color="primary"
                    @click="editFundingSource"
                />
                <q-btn
                    class="gt-xs"
                    size="12px"
                    flat
                    dense
                    round
                    icon="mdi-delete"
                    color="negative"
                    @click="deleteFundingSource"
                />
            </div>
        </q-item-section>
    </q-item>
</template>

<script setup lang="ts">
import { FundingSourceResource } from '../FundingSource';

const props = defineProps<{
    readonly?: boolean;
    fundingSource: FundingSourceResource;
}>();

defineEmits<{
    (event: 'edit:funding-source', fundingSource: FundingSourceResource): void;
    (
        event: 'delete:funding-source',
        fundingSource: FundingSourceResource
    ): void;
}>();

async function editFundingSource() {
    console.log('edit funding source');
}

async function deleteFundingSource() {
    console.log('delete funding source');
}
</script>

<style scoped></style>
