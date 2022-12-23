<template>
    <BaseDialog title="Create Funding Source">
        <q-form @submit="createFundingSource">
            <q-card-section>
                <q-select
                    v-model="funder"
                    label="Funder"
                    :options="funders"
                    option-value="id"
                    option-label="name_en"
                    outlined
                    :rules="[(val) => !!val || 'Funder is required']"
                    class="q-mb-md"
                />
                <q-input
                    v-model="title"
                    label="Title"
                    outlined
                    :rules="[
                        (val) => !!val || 'Title is required',
                        (val) =>
                            val.length <= 255 ||
                            'Title must be less than 255 characters',
                    ]"
                    class="q-mb-md"
                />
                <q-input
                    v-model="description"
                    type="textarea"
                    label="Description"
                    outlined
                    :rules="[
                        (val) =>
                            val.length <= 1500 ||
                            'Description must be less than 1500 characters',
                    ]"
                    class="q-mb-md"
                />
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup label="Cancel" color="primary" outline />
                <q-btn label="Create" type="submit" color="primary" />
            </q-card-actions>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { Funder } from '@/models/Funder/Funder';
import {
    FundableType,
    FundingSourceResource,
    FundingSourceService,
} from '../FundingSource';

const funderStore = useFunderStore();
funderStore.getFunders();
const funders = computed(() => funderStore.funders);

const props = defineProps<{
    fundableId: number;
    fundableType: FundableType;
}>();

const emit = defineEmits<{
    (
        event: 'create:funding-source',
        fundingSource: FundingSourceResource
    ): void;
}>();

// form data
const title = ref('');
const description = ref('');
const funder = ref<Funder | null>(null);

async function createFundingSource() {
    if (!funder.value) return; // will have been caught by validation
    const fundingService = new FundingSourceService(props.fundableType);
    const response = await fundingService.create(props.fundableId, {
        title: title.value,
        description: description.value,
        funder_id: funder.value.id,
    });
    emit('create:funding-source', response);
}
</script>

<style scoped></style>
