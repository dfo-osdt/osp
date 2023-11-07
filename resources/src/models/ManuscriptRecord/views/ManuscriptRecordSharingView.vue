<template>
    <ContentCard secondary class="q-mt-md">
        <template #title>{{ $t('mrf.sharing-title') }}</template>
        <template #subtitle>{{ $t('mrf.sharing-subtitle') }}</template>
        <ShareableTable
            v-if="shareables"
            :shareables="shareables"
            :readonly="readonly"
            :loading="loading"
        />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import {
    ShareableResourceList,
    ShareableService,
} from '@/models/Shareable/Shareable';
import ShareableTable from '@/models/Shareable/components/ShareableTable.vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const props = defineProps<{
    id: number;
}>();

const shareableService = new ShareableService('manuscript-records', props.id);
const shareables = ref<ShareableResourceList | undefined>(undefined);
const manuscript = ref<ManuscriptRecordResource | undefined>(undefined);
const loading = ref(false);

onMounted(async () => {
    loading.value = true;
    shareables.value = await shareableService.list();
    manuscript.value = await ManuscriptRecordService.find(props.id);
    loading.value = false;
});

const readonly = computed<boolean>(() => {
    return manuscript.value?.can?.update === false;
});
</script>

<style scoped></style>
