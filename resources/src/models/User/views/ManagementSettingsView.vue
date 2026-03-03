<script setup lang="ts">
import type {
  CreateDelegationRequest,
  ManagementReviewDelegation,
  ManagementReviewDelegationResourceList,
} from '@/models/ManagementReviewDelegation/ManagementReviewDelegation'
import type {
  CreateNotificationGroupMemberRequest,
  NotificationGroupMember,
  NotificationGroupMemberResourceList,
} from '@/models/NotificationGroup/NotificationGroup'
import { useQuasar } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import ContentCard from '@/components/ContentCard.vue'
import DateInput from '@/components/DateInput.vue'
import { ManagementReviewDelegationService } from '@/models/ManagementReviewDelegation/ManagementReviewDelegation'
import {
  NotificationGroupMemberService,
  NotificationGroupMembershipService,
} from '@/models/NotificationGroup/NotificationGroup'
import UserSelect from '@/models/User/components/UserSelect.vue'

const { t } = useI18n()
const $q = useQuasar()
const authStore = useAuthStore()

// --- Delegation ---
const delegations = ref<ManagementReviewDelegationResourceList>({ data: [] })
const delegationsLoading = ref(false)
const showCreateDelegationDialog = ref(false)

const delegateUserId = ref<number | null>(null)
const delegationStartsAt = ref<string | null>(null)
const delegationEndsAt = ref<string | null>(null)
const delegationComment = ref<string | null>(null)
const delegationSaving = ref(false)

const activeDelegation = computed(() => {
  return delegations.value.data.find(d => d.data.is_active) ?? null
})

const today = computed(() => new Date().toISOString().substring(0, 10))

async function loadDelegations() {
  delegationsLoading.value = true
  try {
    delegations.value = await ManagementReviewDelegationService.list()
  }
  finally {
    delegationsLoading.value = false
  }
}

async function createDelegation() {
  if (!delegateUserId.value || !delegationEndsAt.value) {
    return
  }

  delegationSaving.value = true
  try {
    const data: CreateDelegationRequest = {
      delegate_user_id: delegateUserId.value,
      starts_at: delegationStartsAt.value,
      ends_at: delegationEndsAt.value,
      comment: delegationComment.value,
    }
    await ManagementReviewDelegationService.create(data)
    showCreateDelegationDialog.value = false
    resetDelegationForm()
    await loadDelegations()
    $q.notify({
      type: 'positive',
      message: t('management-settings.delegation-created'),
    })
  }
  catch {
    $q.notify({
      type: 'negative',
      message: t('management-settings.delegation-create-error'),
    })
  }
  finally {
    delegationSaving.value = false
  }
}

async function endDelegationEarly(delegation: ManagementReviewDelegation) {
  $q.dialog({
    title: t('management-settings.end-delegation-title'),
    message: t('management-settings.end-delegation-message'),
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      await ManagementReviewDelegationService.destroy(delegation.id)
      await loadDelegations()
      $q.notify({
        type: 'positive',
        message: t('management-settings.delegation-ended'),
      })
    }
    catch {
      $q.notify({
        type: 'negative',
        message: t('management-settings.delegation-end-error'),
      })
    }
  })
}

function resetDelegationForm() {
  delegateUserId.value = null
  delegationStartsAt.value = null
  delegationEndsAt.value = null
  delegationComment.value = null
}

// --- Notification Group ---
const members = ref<NotificationGroupMemberResourceList>({ data: [] })
const membersLoading = ref(false)
const showAddMemberDialog = ref(false)

const memberUserId = ref<number | null>(null)
const memberExpiresAt = ref<string | null>(null)
const memberSaving = ref(false)

const memberships = ref<NotificationGroupMemberResourceList>({ data: [] })
const membershipsLoading = ref(false)

async function loadMembers() {
  membersLoading.value = true
  try {
    members.value = await NotificationGroupMemberService.list()
  }
  finally {
    membersLoading.value = false
  }
}

async function loadMemberships() {
  membershipsLoading.value = true
  try {
    memberships.value = await NotificationGroupMembershipService.list()
  }
  finally {
    membershipsLoading.value = false
  }
}

async function addMember() {
  if (!memberUserId.value) {
    return
  }

  memberSaving.value = true
  try {
    const data: CreateNotificationGroupMemberRequest = {
      member_user_id: memberUserId.value,
      expires_at: memberExpiresAt.value,
    }
    await NotificationGroupMemberService.create(data)
    showAddMemberDialog.value = false
    resetMemberForm()
    await loadMembers()
    $q.notify({
      type: 'positive',
      message: t('management-settings.member-added'),
    })
  }
  catch {
    $q.notify({
      type: 'negative',
      message: t('management-settings.member-add-error'),
    })
  }
  finally {
    memberSaving.value = false
  }
}

async function removeMember(member: NotificationGroupMember) {
  $q.dialog({
    title: t('management-settings.remove-member-title'),
    message: t('management-settings.remove-member-message'),
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      await NotificationGroupMemberService.destroy(member.id)
      await loadMembers()
      $q.notify({
        type: 'positive',
        message: t('management-settings.member-removed'),
      })
    }
    catch {
      $q.notify({
        type: 'negative',
        message: t('management-settings.member-remove-error'),
      })
    }
  })
}

async function selfRemoveFromGroup(member: NotificationGroupMember) {
  $q.dialog({
    title: t('management-settings.self-remove-title'),
    message: t('management-settings.self-remove-message'),
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      await NotificationGroupMembershipService.destroy(member.id)
      await loadMemberships()
      $q.notify({
        type: 'positive',
        message: t('management-settings.self-removed'),
      })
    }
    catch {
      $q.notify({
        type: 'negative',
        message: t('management-settings.self-remove-error'),
      })
    }
  })
}

function resetMemberForm() {
  memberUserId.value = null
  memberExpiresAt.value = null
}

function formatDate(dateStr: string | null): string {
  if (!dateStr) {
    return '-'
  }
  return new Date(dateStr).toLocaleDateString()
}

onMounted(() => {
  loadDelegations()
  loadMembers()
  loadMemberships()
})
</script>

<template>
  <!-- Delegation Card -->
  <ContentCard secondary class="q-mb-lg">
    <template #title>
      {{ t('management-settings.delegation-title') }}
    </template>
    <template #subtitle>
      {{ t('management-settings.delegation-subtitle') }}
    </template>

    <div v-if="activeDelegation" class="q-mb-md">
      <q-banner class="bg-blue-1 text-primary" rounded>
        <template #avatar>
          <q-icon name="mdi-account-switch" color="primary" />
        </template>
        <div>
          <strong>{{ t('management-settings.active-delegation') }}:</strong>
          {{ activeDelegation.data.delegate?.data.first_name }}
          {{ activeDelegation.data.delegate?.data.last_name }}
        </div>
        <div class="text-caption">
          {{ t('management-settings.ends-at') }}: {{ formatDate(activeDelegation.data.ends_at) }}
        </div>
        <template #action>
          <q-btn
            flat
            color="negative"
            :label="t('management-settings.end-early')"
            @click="endDelegationEarly(activeDelegation.data)"
          />
        </template>
      </q-banner>
    </div>

    <div class="q-mb-md text-right">
      <q-btn
        outline
        color="primary"
        icon="mdi-plus"
        :label="t('management-settings.create-delegation')"
        :disable="!!activeDelegation || authStore.user?.is_acting_as_delegate"
        @click="showCreateDelegationDialog = true"
      >
        <q-tooltip v-if="authStore.user?.is_acting_as_delegate">
          {{ t('management-settings.cannot-delegate-while-acting') }}
        </q-tooltip>
      </q-btn>
    </div>

    <q-table
      :rows="delegations.data"
      :columns="[
        { name: 'delegate', label: t('management-settings.delegate'), field: (row: any) => `${row.data.delegate?.data.first_name} ${row.data.delegate?.data.last_name}`, align: 'left' },
        { name: 'starts_at', label: t('management-settings.starts-at'), field: (row: any) => formatDate(row.data.starts_at), align: 'left' },
        { name: 'ends_at', label: t('management-settings.ends-at'), field: (row: any) => formatDate(row.data.ends_at), align: 'left' },
        { name: 'status', label: t('management-settings.status'), field: (row: any) => row.data.is_active, align: 'left' },
      ]"
      :loading="delegationsLoading"
      flat
      bordered
      :rows-per-page-options="[10]"
      :no-data-label="t('management-settings.no-delegations')"
    >
      <template #body-cell-status="props">
        <q-td :props="props">
          <q-badge
            :color="props.row.data.is_active ? 'positive' : props.row.data.ended_early_at ? 'warning' : 'grey'"
            :label="props.row.data.is_active ? t('management-settings.active') : props.row.data.ended_early_at ? t('management-settings.ended-early') : t('management-settings.expired')"
          />
        </q-td>
      </template>
    </q-table>
  </ContentCard>

  <!-- Notification Group Card -->
  <ContentCard secondary class="q-mb-lg">
    <template #title>
      {{ t('management-settings.notification-group-title') }}
    </template>
    <template #subtitle>
      {{ t('management-settings.notification-group-subtitle') }}
    </template>

    <div class="q-mb-md text-right">
      <q-btn
        outline
        color="primary"
        icon="mdi-plus"
        :label="t('management-settings.add-member')"
        @click="showAddMemberDialog = true"
      />
    </div>

    <q-table
      :rows="members.data"
      :columns="[
        { name: 'member', label: t('management-settings.member'), field: (row: any) => `${row.data.member?.data.first_name} ${row.data.member?.data.last_name}`, align: 'left' },
        { name: 'email', label: t('management-settings.email'), field: (row: any) => row.data.member?.data.email, align: 'left' },
        { name: 'expires_at', label: t('management-settings.expires-at'), field: (row: any) => formatDate(row.data.expires_at), align: 'left' },
        { name: 'actions', label: '', field: 'actions', align: 'right' },
      ]"
      :loading="membersLoading"
      flat
      bordered
      :rows-per-page-options="[10]"
      :no-data-label="t('management-settings.no-members')"
    >
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            flat
            round
            icon="mdi-delete"
            color="negative"
            size="sm"
            @click="removeMember(props.row.data)"
          />
        </q-td>
      </template>
    </q-table>

    <!-- Groups I Belong To -->
    <div class="q-mt-lg">
      <div class="text-subtitle1 text-weight-medium q-mb-sm">
        {{ t('management-settings.groups-i-belong-to') }}
      </div>
      <q-table
        :rows="memberships.data"
        :columns="[
          { name: 'owner', label: t('management-settings.group-owner'), field: (row: any) => `${row.data.user?.data.first_name} ${row.data.user?.data.last_name}`, align: 'left' },
          { name: 'email', label: t('management-settings.email'), field: (row: any) => row.data.user?.data.email, align: 'left' },
          { name: 'expires_at', label: t('management-settings.expires-at'), field: (row: any) => formatDate(row.data.expires_at), align: 'left' },
          { name: 'actions', label: '', field: 'actions', align: 'right' },
        ]"
        :loading="membershipsLoading"
        flat
        bordered
        :rows-per-page-options="[10]"
        :no-data-label="t('management-settings.no-memberships')"
      >
        <template #body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              flat
              round
              icon="mdi-logout"
              color="negative"
              size="sm"
              @click="selfRemoveFromGroup(props.row.data)"
            >
              <q-tooltip>{{ t('management-settings.leave-group') }}</q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
  </ContentCard>

  <!-- Create Delegation Dialog -->
  <BaseDialog
    v-if="showCreateDelegationDialog"
    v-model="showCreateDelegationDialog"
    :title="t('management-settings.create-delegation')"
    @close="showCreateDelegationDialog = false"
  >
    <q-card-section>
      <UserSelect
        v-model="delegateUserId"
        :disabled-ids="[authStore.user?.id ?? 0]"
        required
        class="q-mb-md"
      />
      <DateInput
        v-model="delegationStartsAt"
        :label="t('management-settings.starts-at')"
        :min-date="today"
        class="q-mb-md"
      />
      <DateInput
        v-model="delegationEndsAt"
        :label="t('management-settings.ends-at')"
        :min-date="delegationStartsAt || today"
        required
        class="q-mb-md"
      />
      <q-input
        v-model="delegationComment"
        :label="t('management-settings.comment')"
        :hint="t('management-settings.comment-hint')"
        outlined
        type="textarea"
        rows="3"
      />
    </q-card-section>
    <q-card-actions align="right">
      <q-btn
        v-close-popup
        flat
        :label="t('common.cancel')"
      />
      <q-btn
        color="primary"
        :label="t('common.save')"
        :loading="delegationSaving"
        :disable="!delegateUserId || !delegationEndsAt"
        @click="createDelegation"
      />
    </q-card-actions>
  </BaseDialog>

  <!-- Add Member Dialog -->
  <BaseDialog
    v-if="showAddMemberDialog"
    v-model="showAddMemberDialog"
    :title="t('management-settings.add-member')"
    @close="showAddMemberDialog = false"
  >
    <q-card-section>
      <UserSelect
        v-model="memberUserId"
        :disabled-ids="[authStore.user?.id ?? 0]"
        required
        class="q-mb-md"
      />
      <DateInput
        v-model="memberExpiresAt"
        :label="t('management-settings.expires-at')"
        :min-date="today"
      />
    </q-card-section>
    <q-card-actions align="right">
      <q-btn
        v-close-popup
        flat
        :label="t('common.cancel')"
      />
      <q-btn
        color="primary"
        :label="t('common.save')"
        :loading="memberSaving"
        :disable="!memberUserId"
        @click="addMember"
      />
    </q-card-actions>
  </BaseDialog>
</template>

<style scoped></style>
