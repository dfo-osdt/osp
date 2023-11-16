<template>
    <BaseDialog :title="title">
        <q-card-section class="q-mt-md">
            <q-form class="q-gutter-md" autofocus @submit="save">
                <p v-if="!isEditDialog">
                    User with whom you want to share. The user will receive an
                    email notification once you create this resource.
                </p>
                <UserSelect v-model="user_id" :readonly="isEditDialog" />
                <div>
                    By default, the user has the ability to view the ressource.
                    You can also allow th user to edit and delete this resource.
                </div>
                <q-card bordered flat class="q-pa-sm">
                    <q-toggle
                        v-model="can_edit"
                        label="Can Edit"
                        color="primary"
                        checked-icon="mdi-check"
                        unchecked-icon="mdi-close"
                        size="md"
                    />
                    <q-toggle
                        v-model="can_delete"
                        label="Can Delete"
                        color="primary"
                        checked-icon="mdi-check"
                        unchecked-icon="mdi-close"
                        size="md"
                    />
                </q-card>
                <div>
                    By default, the resource is shared until you manually remove
                    the ability. If you prefer, you can also set an expiration
                    date at which time the user will no longer be able to access
                    this resource.
                </div>
                <DateInput v-model="expires_at" label="Expires At" clearable />
                <div class="flex justify-end">
                    <q-btn
                        v-close-popup
                        :label="$t('common.cancel')"
                        class="q-mr-md"
                    />
                    <q-btn
                        :label="$t('common.save')"
                        type="submit"
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
    }>(),
    {
        shareable: undefined,
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

async function save() {
    if (props.shareable) {
        const updatedShareable = await shareableService.update({
            id: props.shareable.id,
            can_edit: can_edit.value,
            can_delete: can_delete.value,
            expires_at: expires_at.value,
        });
        emit('updated', updatedShareable);
    }
}
</script>
<style scoped></style>
