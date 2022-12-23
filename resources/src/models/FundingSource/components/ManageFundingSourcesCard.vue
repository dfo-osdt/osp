<template>
    <ContentCard class="q-mb-lg" secondary>
        <template #title>Funding Sources</template>
        <template #title-right
            ><FormSectionStatusIcon status="complete" />
        </template>
        <p>
            If applicable, please enter the funding source(s) that have help
            generate this work.
        </p>
        <FundingSourceList :funding-sources="fundingSources" />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue';
import {
    FundingSourceRelationship,
    FundingSourceResource,
    FundingSourceService,
} from '../FundingSource';
import FundingSourceList from './FundingSourceList.vue';

const props = defineProps<{
    fundableId: number;
    fundableType: FundingSourceRelationship;
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
</script>

<style scoped></style>
