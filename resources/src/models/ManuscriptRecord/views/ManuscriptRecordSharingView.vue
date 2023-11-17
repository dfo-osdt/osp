<template>
    <ContentCard secondary class="q-mt-md">
        <template #title>{{ $t('mrf.sharing-title') }}</template>
        <template #subtitle>{{ $t('mrf.sharing-subtitle') }}</template>
        <ShareableTable
            v-if="shareables"
            :shareables="shareables"
            :readonly="readonly"
            :loading="loading"
            @edit="editShareable"
            @delete="deleteShareable"
        />
        <q-btn
            v-if="!readonly"
            color="primary"
            :label="$t('common.share')"
            icon="share"
            @click="createShareable"
        />
        <ShareItemDialog
            v-if="manuscript && showShareDialog"
            :id="editShareableItem?.id || 0"
            v-model="showShareDialog"
            shareable-type="manuscript-records"
            :shareable="editShareableItem"
            :shareable-model-id="manuscript?.data.id"
            :disabled-user-ids="disabledUserIds"
            @created="shareableCreated"
            @updated="shareableUpdated"
        />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import {
    Shareable,
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
import ShareItemDialog from '@/models/Shareable/components/ShareItemDialog.vue';

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

const authStore = useAuthStore();
const readonly = computed<boolean>(() => {
    return manuscript.value?.data.user_id !== authStore.user?.id;
});

const $q = useQuasar();
const { t } = useI18n();

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

/**
 * Create or Edit Shareable Section
 */
const showShareDialog = ref(false);
const editShareableItem = ref<Shareable | undefined>(undefined);
const disabledUserIds = computed<number[]>(() => {
    const ids = shareables.value?.data.map((s) => s.data.user.data.id) || [];
    if (manuscript.value?.data.user_id !== undefined) {
        ids.push(manuscript.value.data.user_id);
    }
    return ids;
});

function createShareable() {
    editShareableItem.value = undefined;
    showShareDialog.value = true;
}

function editShareable(shareable: ShareableResource) {
    if (shareable.data == undefined) {
        throw new Error('Shareable data is undefined');
    }
    editShareableItem.value = shareable.data;
    showShareDialog.value = true;
}

function shareableCreated(shareable: ShareableResource) {
    shareables.value?.data.push(shareable);
    showShareDialog.value = false;
}

function shareableUpdated(shareable: ShareableResource) {
    const index = shareables.value?.data.findIndex(
        (s) => s.data.id === shareable.data.id,
    );
    if (index !== undefined && index !== -1) {
        shareables.value?.data.splice(index, 1, shareable);
    }
    showShareDialog.value = false;
}
</script>

<style scoped></style>
