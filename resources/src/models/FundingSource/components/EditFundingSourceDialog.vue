<template>
    <BaseDialog title="Edit Funding Source">
        <q-form @submit="editFundingSource">
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
                <q-btn label="Save" type="submit" color="primary" />
            </q-card-actions>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { Funder } from '@/models/Funder/Funder';
import {
    FundingSource,
    FundingSourceResource,
    FundingSourceService,
} from '../FundingSource';

const funderStore = useFunderStore();
funderStore.getFunders();
const funders = computed(() => funderStore.funders);

const props = defineProps<{
    fundingSource: FundingSource;
}>();

const emit = defineEmits<{
    (
        event: 'edited:funding-source',
        fundingSource: FundingSourceResource
    ): void;
}>();

// form data
const title = ref(props.fundingSource.title);
const description = ref(props.fundingSource.description ?? '');
const funder = ref<Funder | null>(props.fundingSource.funder?.data ?? null);

async function editFundingSource() {
    if (!funder.value) return; // will have been caught by validation
    const fundingService = new FundingSourceService(
        props.fundingSource.fundable_type
    );
    const response = await fundingService.update({
        id: props.fundingSource.id,
        title: title.value,
        description: description.value,
        funder_id: funder.value.id,
        fundable_type: props.fundingSource.fundable_type,
        fundable_id: props.fundingSource.fundable_id,
    });
    emit('edited:funding-source', response);
}
</script>

<style scoped></style>
