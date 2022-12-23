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
                    @click="showEditDialog = true"
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
        <EditFundingSourceDialog
            v-if="showEditDialog"
            v-model="showEditDialog"
            :funding-source="fundingSource.data"
            @edited:funding-source="editedFundingSource"
        ></EditFundingSourceDialog>
    </q-item>
</template>

<script setup lang="ts">
import { FundingSourceResource } from '../FundingSource';
import EditFundingSourceDialog from './EditFundingSourceDialog.vue';

const props = defineProps<{
    readonly?: boolean;
    fundingSource: FundingSourceResource;
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

async function deleteFundingSource() {
    emit('delete:funding-source', props.fundingSource);
}

// edit dialog
const showEditDialog = ref(false);

async function editedFundingSource(fundingSource: FundingSourceResource) {
    showEditDialog.value = false;
    emit('edited:funding-source', fundingSource);
}
</script>

<style scoped></style>
