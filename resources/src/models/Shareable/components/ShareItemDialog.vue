<template>
    <BaseDialog :title="title"> We made it here! </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { ShareableModel, Shareable } from '@/models/Shareable/Shareable';

const props = withDefaults(
    defineProps<{
        shareableType: ShareableModel;
        shareableModelId: number | string;
        shareable: undefined | Shareable;
    }>(),
    {
        shareable: undefined,
    },
);

const { t } = useI18n();

const title = computed(() => {
    if (props.shareable == undefined) {
        switch (props.shareableType) {
            case 'manuscript-records':
                return t('mrf.share-dialog-title');
            default:
                return 'Create Shareable';
        }
    }
    return t('shareable.edit-dialog-title');
});
</script>
<style scoped></style>
