<template>
    <BaseDialog :title="title">
        <q-card-section class="q-mt-md">
            <q-form ref="form" class="q-gutter-md" autofocus @submit="save">
                <p v-if="!isEditDialog">
                    {{ $t('shareable-dialog.user-create-text') }}
                </p>
                <UserSelect
                    v-model="user_id"
                    :readonly="isEditDialog"
                    :required="!isEditDialog"
                    :disabled-ids="disabledUserIds"
                />
                <div>
                    {{ $t('shareable-dialog.abilities-text') }}
                </div>
                <q-card bordered flat class="q-pa-sm">
                    <q-toggle
                        v-model="can_edit"
                        :label="$t('shareable.can-edit')"
                        color="primary"
                        checked-icon="mdi-check"
                        unchecked-icon="mdi-close"
                        size="md"
                    />
                    <q-toggle
                        v-model="can_delete"
                        :label="$t('shareable.can-delete')"
                        color="primary"
                        checked-icon="mdi-check"
                        unchecked-icon="mdi-close"
                        size="md"
                    />
                </q-card>
                <div>
                    {{ $t('shareable-dialog.expiry-date-text') }}
                </div>
                <DateInput
                    v-model="expires_at"
                    label="Expires On"
                    :min-date="tomorrow"
                    clearable
                />
                <div class="flex justify-end">
                    <q-btn
                        v-close-popup
                        :label="$t('common.cancel')"
                        class="q-mr-md"
                    />
                    <q-btn
                        :label="
                            isEditDialog
                                ? $t('common.save')
                                : $t('common.share')
                        "
                        type="submit"
                        :loading="loading"
                        color="primary"
                        data-cy="save"
                    />
                </div>
            </q-form>
        </q-card-section>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import DateInput from '@/components/DateInput.vue';
import {
    ShareableModel,
    Shareable,
    ShareableService,
    ShareableResource,
} from '@/models/Shareable/Shareable';
import UserSelect from '@/models/User/components/UserSelect.vue';

const props = withDefaults(
    defineProps<{
        shareableType: ShareableModel;
        shareableModelId: number | string;
        shareable: undefined | Shareable;
        disabledUserIds?: number[];
    }>(),
    {
        shareable: undefined,
        disabledUserIds: () => [],
    },
);

const emit = defineEmits<{
    updated: [sharable: ShareableResource];
    created: [sharable: ShareableResource];
}>();

const { t } = useI18n();

const isEditDialog = computed(() => {
    return props.shareable != undefined;
});

const tomorrow = computed(() => {
    const date = new Date();
    date.setDate(date.getDate() + 1);
    return date.toISOString().substring(0, 10);
});

const title = computed(() => {
    if (!isEditDialog.value) {
        switch (props.shareableType) {
            case 'manuscript-records':
                return t('mrf.share-dialog-title');
            default:
                return 'Create Shareable';
        }
    }
    return t('shareable.edit-dialog-title');
});

const user_id = ref(props.shareable?.user_id || null);
const can_edit = ref(props.shareable?.can_edit || false);
const can_delete = ref(props.shareable?.can_delete || false);
const expires_at = ref(props.shareable?.expires_at || null);

// logic to ensure permission consistency
watch(can_delete, (value) => {
    if (value) {
        can_edit.value = true;
    }
});
watch(can_edit, (value) => {
    if (!value) {
        can_delete.value = false;
    }
});

const shareableService = new ShareableService(
    props.shareableType,
    props.shareableModelId,
);

const loading = ref(false);
async function save() {
    loading.value = true;

    // date sent to server is in UTC, so we need to add the timezone offset
    if (expires_at.value) {
        const date = new Date(expires_at.value);
        date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
        expires_at.value = date.toISOString();
    }

    if (props.shareable) {
        const updatedShareable = await shareableService.update({
            id: props.shareable.id,
            can_edit: can_edit.value,
            can_delete: can_delete.value,
            expires_at: expires_at.value,
        });
        emit('updated', updatedShareable);
        return;
    }

    if (!user_id.value) {
        return;
    }

    const createShareable = await shareableService.create({
        user_id: user_id.value,
        can_edit: can_edit.value,
        can_delete: can_delete.value,
        expires_at: expires_at.value,
    });
    emit('created', createShareable);
}
</script>
<style scoped></style>
