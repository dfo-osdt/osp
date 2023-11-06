<template>
    <ContentCard secondary class="q-mt-md">
        <template #title>{{ $t('manuscript-record.sharing') }}</template>
        <template #subtitle
            >Share this manuscript with other portal users</template
        >
        <ShareableTable v-if="shareables" :shareables="shareables" />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import {
    ShareableResourceList,
    ShareableService,
} from '@/models/Shareable/Shareable';
import ShareableTable from '@/models/Shareable/components/ShareableTable.vue';

const props = defineProps<{
    id: number;
}>();

const shareableService = new ShareableService('manuscript-records', props.id);
const shareables = ref<ShareableResourceList | undefined>(undefined);

onMounted(async () => {
    shareables.value = await shareableService.list();
});
</script>

<style scoped></style>
