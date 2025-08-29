<script setup lang="ts">
import type { QTableProps } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import TimeStampTd from './Table/TimeStampTd.vue'

const authStore = useAuthStore()
authStore.getAuthentications()
const { t } = useI18n()

// table logic
type QTableColumnProps = QTableProps['columns']
const columns = computed<QTableColumnProps>(() => {
  return [
    {
      name: 'ip_address',
      label: t('authentication-history-card.ip_address'),
      field: 'ip_address',
      align: 'left',
      sortable: true,
    },
    {
      name: 'user_agent',
      label: t('authentication-history-card.browser'),
      field: 'user_agent_for_humans',
      align: 'left',
      sortable: true,
    },
    {
      name: 'location',
      label: t('authentication-history-card.location'),
      field: (row) => {
        return row.location
          ? `${row.location.city} - ${row.location.state}`
          : '-'
      },
      align: 'left',
      sortable: true,
    },
    {
      name: 'login_at',
      label: t('authentication-history-card.login-at'),
      field: 'login_at',
      align: 'left',
      sortable: true,
    },
    {
      name: 'login_successful',
      label: t('authentication-history-card.successful'),
      field: 'login_successful',
      align: 'left',
      sortable: true,
    },
    {
      name: 'logout_at',
      label: t('authentication-history-card.logout-at'),
      field: 'logout_at',
      align: 'left',
      sortable: true,
    },
  ]
})
</script>

<template>
  <ContentCard secondary>
    <template #title>
      {{
        t('authentication-history-card.title')
      }}
    </template>
    <template #subtitle>
      {{
        t('authentication-history-card.subtitle')
      }}
    </template>
    <q-table
      flat
      :columns="columns"
      :loading="authStore.authenticationsLoading"
      :rows="authStore.userAuthentications ?? []"
    >
      <template #body-cell-login_at="props">
        <TimeStampTd :props="props" />
      </template>
      <template #body-cell-logout_at="props">
        <TimeStampTd :props="props" />
      </template>
      <template #body-cell-login_successful="props">
        <q-td :props="props">
          <template v-if="props.value">
            <q-icon
              name="mdi-check-circle-outline"
              color="green-6"
              size="1.5em"
            />
          </template>
          <template v-else>
            <q-icon
              name="mdi-alert-circle-outline"
              color="red-6"
              size="1.5em"
            />
          </template>
        </q-td>
      </template>
    </q-table>
  </ContentCard>
</template>

<style scoped></style>
