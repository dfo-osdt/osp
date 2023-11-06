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
            field: (row: ShareableResource) =>
                `${row.data.user.data.first_name} ${row.data.user.data.last_name}`,
        },
    ];
});
</script>

<style scoped></style>
