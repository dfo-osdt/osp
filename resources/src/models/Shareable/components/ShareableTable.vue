<template>
    <q-table flat :rows="shareables.data" :columns="columns" row-key="id" />
</template>

<script setup lang="ts">
import { QTableProps } from 'quasar';
import { ShareableResource, ShareableResourceList } from '../Shareable';

const props = defineProps<{
    shareables: ShareableResourceList;
}>();

type QTableColumnProps = QTableProps['columns'];
const columns = computed<QTableColumnProps>(() => {
    return [
        {
            name: 'user',
            label: 'User',
            align: 'left',
            field: (row: ShareableResource) =>
                `${row.data.user.data.first_name} ${row.data.user.data.last_name}`,
        },
        {
            name: 'canEdit',
            label: 'Can Edit',
            align: 'left',
            field: (row: ShareableResource) =>
                row.data.can_edit ? 'Yes' : 'No',
        },
        {
            name: 'canDelete',
            label: 'Can Delete',
            align: 'left',
            field: (row: ShareableResource) =>
                row.data.can_delete ? 'Yes' : 'No',
        },
        {
            name: 'expiresAt',
            label: 'Expires At',
            align: 'left',
            field: (row: ShareableResource) => row.data.expires_at ?? 'Never',
        },
    ];
});
</script>

<style scoped></style>
