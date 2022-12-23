<template>
    <ContentCard class="q-mb-lg" secondary>
        <template #title>Funding Sources</template>
        <template #title-right
            ><FormSectionStatusIcon status="complete" />
        </template>
        <p>
            If applicable, please enter the funding source(s) that have helped
            generate this work.
        </p>
        <FundingSourceList
            :funding-sources="fundingSources"
            :fundable-type="fundableType"
            :readonly="readonly"
            @edited:funding-source="editFundingSource"
            @delete:funding-source="deleteFundingSource"
        />
        <q-card-actions class="justify-end q-px-none q-pt-md">
            <q-btn
                v-if="!readonly"
                icon="mdi-plus"
                label="Add Funding Source"
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
            @create:funding-source="createFundingSource"
        />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue';
import {
    FundableType,
    FundingSourceResource,
    FundingSourceService,
} from '../FundingSource';
import FundingSourceList from './FundingSourceList.vue';
import { useQuasar } from 'quasar';
import CreateFundingSourceDialog from './CreateFundingSourceDialog.vue';
const $q = useQuasar();

const props = defineProps<{
    fundableId: number;
    fundableType: FundableType;
    readonly?: boolean;
}>();

const loading = ref(true);
const fundingSources = ref<FundingSourceResource[]>([]);

onMounted(async () => {
    await loadFundingSources();
});

async function loadFundingSources() {
    loading.value = true;
    const fundingService = new FundingSourceService(props.fundableType);
    const response = await fundingService.all(props.fundableId);
    fundingSources.value = response.data;
    loading.value = false;
}

async function createFundingSource(fundingSource: FundingSourceResource) {
    fundingSources.value.push(fundingSource);
    $q.notify({
        message: 'Funding source created.',
        color: 'positive',
        icon: 'mdi-check-circle',
    });
    // close dialog
    showCreateDialog.value = false;
}

async function editFundingSource(fundingSource: FundingSourceResource) {
    const index = fundingSources.value.findIndex(
        (fs) => fs.data.id === fundingSource.data.id
    );
    fundingSources.value.splice(index, 1, fundingSource);
}

async function deleteFundingSource(fundingSource: FundingSourceResource) {
    const fundingService = new FundingSourceService(props.fundableType);
    try {
        await fundingService.delete(fundingSource.data);
        loadFundingSources();
        $q.notify({
            message: 'Funding source deleted.',
            color: 'positive',
            icon: 'mdi-check-circle',
        });
    } catch (err) {
        console.log(err);
    }
}

// create dialog
const showCreateDialog = ref(false);
</script>

<style scoped></style>
