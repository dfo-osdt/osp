<script setup lang="ts">
import type { QTableProps } from 'quasar'
import type { UserInvitationResource } from '../AuthenticatedUser'
import ContentCard from '@/components/ContentCard.vue'
import {
  AuthenticatedUserService,

} from '../AuthenticatedUser'
import TimeStampTd from './Table/TimeStampTd.vue'

const authStore = useAuthStore()
authStore.getAuthentications()
const { t } = useI18n()

// get invitations
const loading = ref(true)
const invitations = ref<UserInvitationResource[]>([])

onMounted(async () => {
  loading.value = true
  try {
    const response = await AuthenticatedUserService.getSentInvitations()
    invitations.value = response.data
  }
  catch (e) {
    console.log(e)
  }
  finally {
    loading.value = false
  }
})

// table logic
type QTableColumnProps = QTableProps['columns']
const columns = computed<QTableColumnProps>(() => {
  return [
    {
      name: 'registered',
      label: t('common.status'),
      field: (row: UserInvitationResource) =>
        row.data.registered_at !== null,
      align: 'left',
      sortable: true,
    },
    {
      name: 'user',
      label: t('common.user'),
      field: (row: UserInvitationResource) =>
        `${row.data.user.data.first_name} ${row.data.user.data.last_name}`,
      align: 'left',
      sortable: true,
    },
    {
      name: 'email',
      label: t('common.email'),
      field: (row: UserInvitationResource) => row.data.user.data.email,
      align: 'left',
      sortable: true,
    },
    {
      name: 'invited_at',
      label: t('SentInvitationsCard.invited-at'),
      field: (row: UserInvitationResource) => row.data.invited_at,
      align: 'left',
      sortable: true,
    },
    {
      name: 'registered_at',
      label: t('SentInvitationsCard.registered-at'),
      field: (row: UserInvitationResource) => row.data.registered_at,
      align: 'left',
      sortable: true,
    },
  ]
})
</script>

<template>
  <ContentCard secondary>
    <template #title>
      {{ $t('SentInvitationsCard.title') }}
    </template>
    <template #subtitle>
      {{ $t('SentInvitationsCard.subtitle') }}
    </template>
    <q-table flat :columns="columns" :loading="loading" :rows="invitations">
      <template #body-cell-invited_at="props">
        <TimeStampTd :props="props" />
      </template>
      <template #body-cell-registered_at="props">
        <TimeStampTd :props="props" />
      </template>
      <template #body-cell-registered="props">
        <q-td :props="props">
          <template v-if="props.value">
            <q-icon
              name="mdi-check-circle-outline"
              color="green-6"
              size="1.5em"
            /><span class="q-ml-sm">{{
              $t('common.registered')
            }}</span>
          </template>
          <template v-else>
            <q-icon
              name="mdi-clock-outline"
              color="secondary"
              size="1.5em"
            /><span class="q-ml-sm">{{
              $t('common.pending')
            }}</span>
          </template>
        </q-td>
      </template>
    </q-table>
  </ContentCard>
</template>

<style scoped></style>
