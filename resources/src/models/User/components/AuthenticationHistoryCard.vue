<template>
    <ContentCard secondary>
        <template #title>Authentication History</template>
        <template #subtitle
            >Suspicious activity? Change your password and report it to
            us.</template
        >
        <q-table
            flat
            :columns="columns"
            :rows="authStore.userAuthentications ?? []"
        >
            <template #body-cell-login_at="props">
                <q-td :props="props"
                    >{{
                        new Date(props.value).toLocaleString(
                            `${$i18n.locale}-CA`,
                            {
                                // simple time stamp format
                                dateStyle: 'short',
                                timeStyle: 'short',
                            }
                        )
                    }}
                    <span class="text-grey-8"
                        >(
                        {{ useLocaleTimeAgo(new Date(props.value)).value }}
                        )</span
                    ></q-td
                >
            </template>
        </q-table>
        <pre>{{ authStore.userAuthentications }}</pre>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { useLocaleTimeAgo } from '@/composables/useLocaleTimeAgo';
import { QTableProps } from 'quasar';

const authStore = useAuthStore();
authStore.getAuthentications();

// table logic
type QTableColumnProps = QTableProps['columns'];
const columns = computed<QTableColumnProps>(() => {
    return [
        {
            name: 'login_at',
            label: 'Login At',
            field: 'login_at',
            align: 'left',
            sortable: true,
        },
        {
            name: 'ip_address',
            label: 'IP Address',
            field: 'ip_address',
            align: 'left',
            sortable: true,
        },
    ];
});
</script>

<style scoped></style>
