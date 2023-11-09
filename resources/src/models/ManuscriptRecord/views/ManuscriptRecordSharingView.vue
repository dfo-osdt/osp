<template>
    <ContentCard secondary class="q-mt-md">
        <template #title>{{ $t('mrf.sharing-title') }}</template>
        <template #subtitle>{{ $t('mrf.sharing-subtitle') }}</template>
        <ShareableTable
            v-if="shareables"
            :shareables="shareables"
            :readonly="readonly"
            :loading="loading"
            @delete="deleteShareable"
        />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import {
    ShareableResource,
    ShareableResourceList,
    ShareableService,
} from '@/models/Shareable/Shareable';
import ShareableTable from '@/models/Shareable/components/ShareableTable.vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';
import { useQuasar } from 'quasar';

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

const $q = useQuasar();
const { t } = useI18n();

async function updateShareables(shareable: ShareableResource) {
    loading.value = true;
    await shareableService.update(shareable.data);
    loading.value = false;
}

async function deleteShareable(shareable: ShareableResource) {
    loading.value = true;
    console.log(shareable);
    $q.dialog({
        title: t('mrf.sharing-confirm-delete-title'),
        message: t('mrf.sharing-confirm-delete-message', {
            userName: `${shareable.data.user.data.first_name} ${shareable.data.user.data.last_name}`,
        }),
        cancel: true,
        persistent: true,
    }).onOk(async () => {
        await shareableService.delete(shareable.data.id);
        shareables.value = await shareableService.list();
    });
    loading.value = false;
}
</script>

<style scoped></style>
