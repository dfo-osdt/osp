<template>
    <q-table flat :rows="shareables.data" :columns="columns" row-key="id">
        <template #body-cell-canEdit="props">
            <q-td :props="props">
                <q-icon
                    size="sm"
                    :name="
                        props.row.data.can_edit
                            ? 'mdi-check-circle-outline'
                            : 'mdi-close-circle-outline'
                    "
                    :color="props.row.data.can_edit ? 'positive' : 'negative'"
                    :aria-label="
                        props.row.data.can_edit
                            ? $t('common.yes')
                            : $t('common.no')
                    "
                />
            </q-td>
        </template>
        <template #body-cell-canDelete="props">
            <q-td :props="props">
                <q-icon
                    size="sm"
                    :name="
                        props.row.data.can_delete
                            ? 'mdi-check-circle-outline'
                            : 'mdi-close-circle-outline'
                    "
                    :color="props.row.data.can_delete ? 'positive' : 'negative'"
                    :aria-label="
                        props.row.data.can_delete
                            ? $t('common.yes')
                            : $t('common.no')
                    "
                />
            </q-td>
        </template>
        <template #body-cell-expiresAt="props">
            <time-stamp-td :props="props" />
        </template>
        <template #body-cell-actions="props">
            <q-td :props="props">
                <q-btn
                    flat
                    dense
                    round
                    :disable="readonly"
                    size="sm"
                    icon="edit"
                    color="primary"
                    @click="editShareable(props.row)"
                />
                <q-btn
                    flat
                    dense
                    round
                    :disable="readonly"
                    size="sm"
                    icon="delete"
                    color="negative"
                    @click="deleteShareable(props.row)"
                />
            </q-td>
        </template>
    </q-table>
</template>

<script setup lang="ts">
import { QTableProps } from 'quasar';
import { ShareableResource, ShareableResourceList } from '../Shareable';
import TimeStampTd from '@/models/User/components/Table/TimeStampTd.vue';

const { t } = useI18n();

withDefaults(
    defineProps<{
        shareables: ShareableResourceList;
        readonly?: boolean;
    }>(),
    {
        readonly: false,
    },
);

type QTableColumnProps = QTableProps['columns'];
const columns = computed<QTableColumnProps>(() => {
    return [
        {
            name: 'user',
            label: t('common.user'),
            align: 'left',
            field: (row: ShareableResource) =>
                `${row.data.user.data.first_name} ${row.data.user.data.last_name}`,
        },
        {
            name: 'email',
            label: t('common.email'),
            align: 'left',
            field: (row: ShareableResource) => row.data.user.data.email,
        },
        {
            name: 'canEdit',
            label: t('shareable.can-edit'),
            align: 'left',
            field: (row: ShareableResource) =>
                row.data.can_edit ? t('common.yes') : t('common.no'),
        },
        {
            name: 'canDelete',
            label: t('shareable.can-delete'),
            align: 'left',
            field: (row: ShareableResource) =>
                row.data.can_delete ? t('common.yes') : t('common.no'),
        },
        {
            name: 'expiresAt',
            label: t('shareable.expires-at'),
            align: 'left',
            field: (row: ShareableResource) => row.data.expires_at,
        },
        {
            name: 'actions',
            label: t('common.actions'),
            align: 'right',
            field: 'actions',
        },
    ];
});

const emit = defineEmits<{
    edit: [shareable: ShareableResource];
    delete: [shareable: ShareableResource];
}>();

function deleteShareable(shareable: ShareableResource) {
    emit('delete', shareable);
}

function editShareable(shareable: ShareableResource) {
    emit('edit', shareable);
}
</script>

<style scoped></style>
