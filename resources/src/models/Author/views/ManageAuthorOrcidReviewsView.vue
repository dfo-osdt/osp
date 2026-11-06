<script setup lang="ts">
import type { OrcidReviewContributionResourceList, OrcidReviewStatus } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import TitleText from '@/components/TitleText.vue'
import { OrcidReviewContributionService } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { useReviewContributorRoleOptions } from '@/models/ManuscriptReviewContributor/reviewContributorRoleOptions'

const { t } = useI18n()
const q = useQuasar()
const authStore = useAuthStore()
const localeStore = useLocaleStore()
const { roleLabel } = useReviewContributorRoleOptions()

// a plain link, not an API call: pass the locale so ORCID shows the same language
const connectUrl = computed(() => `/api/user/orcid/verify?locale=${localeStore.locale}`)

const hasAuthor = computed(() => !!authStore.user?.author?.data)

const contributions = ref<OrcidReviewContributionResourceList | null>(null)
const loading = ref(false)
const updating = ref<number | null>(null)
const addingAll = ref(false)

const hasUndecided = computed(() => contributions.value?.data.some(contribution => contribution.data.orcid_status === 'not_on_orcid') ?? false)

// additions and removals in progress run in the background
function isInProgress(status: OrcidReviewStatus): boolean {
  return status === 'pending' || status === 'removing'
}

// contributions in progress wait for the author to connect ORCID with the write scope
function isWaitingForConnection(status: OrcidReviewStatus): boolean {
  return isInProgress(status) && !contributions.value?.meta.can_write_to_orcid
}

function statusLabel(status: OrcidReviewStatus): string {
  if (isWaitingForConnection(status))
    return t('orcid-review-credit.status.waiting_for_connection')

  const labels: Record<OrcidReviewStatus, string> = {
    not_on_orcid: t('orcid-review-credit.status.not_on_orcid'),
    pending: t('orcid-review-credit.status.pending'),
    on_orcid: t('orcid-review-credit.status.on_orcid'),
    removing: t('orcid-review-credit.status.removing'),
    declined: t('orcid-review-credit.status.declined'),
  }
  return labels[status]
}

function statusColor(status: OrcidReviewStatus): string {
  return status === 'on_orcid'
    ? 'positive'
    : 'grey-8'
}

// contributions being added or removed are synced in the background: reload until ORCID
// is up to date, for a minute at most (e.g. it stays pending while DFO has no ROR identifier)
const pollIntervalMs = 2000
const maxPolls = 30
let pollCount = 0
let pollTimeoutId: ReturnType<typeof setTimeout> | null = null

function stopPolling() {
  if (pollTimeoutId)
    clearTimeout(pollTimeoutId)
  pollTimeoutId = null
}

function pollWhileInProgress() {
  stopPolling()
  const hasInProgress = contributions.value?.meta.can_write_to_orcid
    && contributions.value.data.some(contribution => isInProgress(contribution.data.orcid_status))
  if (!hasInProgress || pollCount >= maxPolls)
    return

  pollTimeoutId = setTimeout(async () => {
    pollCount++
    try {
      contributions.value = await OrcidReviewContributionService.list()
      pollWhileInProgress()
    }
    catch {
      // a failed background reload is not worth an error: the page still shows the last list
    }
  }, pollIntervalMs)
}

async function updateContribution(id: number, action: 'add' | 'remove') {
  updating.value = id
  try {
    const updated = await OrcidReviewContributionService[action](id)
    if (!contributions.value)
      return
    const index = contributions.value.data.findIndex(contribution => contribution.data.id === id)
    if (index !== -1)
      contributions.value.data[index] = updated
    pollCount = 0
    pollWhileInProgress()
  }
  catch {
    q.notify({
      type: 'negative',
      message: action === 'add'
        ? t('orcid-review-credit.add-error')
        : t('orcid-review-credit.remove-error'),
    })
  }
  finally {
    updating.value = null
  }
}

async function addAllToOrcid() {
  addingAll.value = true
  try {
    contributions.value = await OrcidReviewContributionService.addAll()
    pollCount = 0
    pollWhileInProgress()
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-review-credit.add-all-error'),
    })
  }
  finally {
    addingAll.value = false
  }
}

const savingAutoSync = ref(false)

async function updateAutoSync(autoSync: boolean) {
  if (!contributions.value)
    return

  savingAutoSync.value = true
  try {
    const response = await OrcidReviewContributionService.updateAutoSync(autoSync)
    contributions.value.meta.auto_sync = response.auto_sync
    q.notify({
      type: 'positive',
      message: autoSync
        ? t('orcid-review-credit.auto-sync-on')
        : t('orcid-review-credit.auto-sync-off'),
    })
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-review-credit.auto-sync-error'),
    })
  }
  finally {
    savingAutoSync.value = false
  }
}

async function loadContributions() {
  loading.value = true
  try {
    contributions.value = await OrcidReviewContributionService.list()
    pollWhileInProgress()
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-review-credit.load-error'),
    })
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  if (hasAuthor.value)
    loadContributions()
})

onBeforeUnmount(stopPolling)
</script>

<template>
  <q-btn
    v-if="hasAuthor"
    flat
    no-caps
    dense
    color="primary"
    icon="mdi-arrow-left"
    class="q-mb-sm"
    :label="t('orcid-review-credit.back')"
    :to="{ name: 'settings.author' }"
  />
  <ContentCard v-if="hasAuthor" secondary class="q-mb-xl" data-test="orcid-review-credit">
    <template #title>
      <q-icon name="mdi-account-check-outline" size="md" />
      <span class="q-pl-sm">{{ t('orcid-review-credit.title') }}</span>
    </template>
    <template #subtitle>
      {{ t('orcid-review-credit.intro') }}
    </template>
    <q-banner
      v-if="contributions && !contributions.meta.can_write_to_orcid"
      class="bg-blue-grey-1 text-grey-9 q-mb-md"
      rounded
      dense
      inline-actions
      data-test="orcid-connect-banner"
    >
      <template #avatar>
        <q-icon name="mdi-information-outline" color="primary" />
      </template>
      {{ t('orcid-review-credit.connect-banner') }}
      <template #action>
        <q-btn outline no-caps color="primary" :href="connectUrl">
          <OrcidAvatar size="sm" />
          <span class="q-ml-sm">{{ t('orcid.verify-btn-text') }}</span>
        </q-btn>
      </template>
    </q-banner>
    <q-inner-loading :showing="loading" />
    <q-list v-if="contributions" bordered class="rounded-borders q-mb-md">
      <q-item>
        <q-item-section avatar top>
          <q-icon name="mdi-autorenew" color="primary" />
        </q-item-section>
        <q-item-section>
          <q-item-label>{{ t('orcid-review-credit.auto-sync') }}</q-item-label>
          <q-item-label caption>
            {{ t('orcid-review-credit.auto-sync-hint') }}
          </q-item-label>
        </q-item-section>
        <q-item-section side top>
          <q-toggle
            :model-value="contributions.meta.auto_sync"
            :aria-label="t('orcid-review-credit.auto-sync')"
            :disable="savingAutoSync"
            data-test="orcid-review-auto-sync"
            @update:model-value="updateAutoSync"
          />
        </q-item-section>
      </q-item>
    </q-list>
    <div v-if="hasUndecided" class="row justify-end q-mb-sm">
      <q-btn
        unelevated
        no-caps
        color="primary"
        icon="mdi-playlist-plus"
        :label="t('orcid-review-credit.add-all')"
        :loading="addingAll"
        :disable="updating !== null"
        data-test="orcid-review-add-all"
        @click="addAllToOrcid"
      />
    </div>
    <q-list v-if="contributions" bordered separator class="rounded-borders">
      <q-item v-for="contribution in contributions.data" :key="contribution.data.id">
        <q-item-section>
          <q-item-label class="text-weight-medium">
            <TitleText :title="contribution.data.publication.title" />
          </q-item-label>
          <q-item-label caption>
            {{ contribution.data.publication.journal.data.title }}
          </q-item-label>
          <q-item-label caption>
            {{ roleLabel(contribution.data.role) }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <div class="row items-center no-wrap q-gutter-sm">
            <q-badge
              outline
              :color="statusColor(contribution.data.orcid_status)"
              :label="statusLabel(contribution.data.orcid_status)"
            >
              <q-tooltip v-if="contribution.data.orcid_status === 'removing' && isWaitingForConnection(contribution.data.orcid_status)">
                {{ t('orcid-review-credit.removing-hint') }}
              </q-tooltip>
            </q-badge>
            <q-btn
              v-if="['not_on_orcid', 'removing', 'declined'].includes(contribution.data.orcid_status)"
              outline
              no-caps
              color="primary"
              icon="mdi-plus"
              :label="t('orcid-review-credit.add')"
              :loading="updating === contribution.data.id"
              :disable="addingAll"
              data-test="orcid-review-add"
              @click="updateContribution(contribution.data.id, 'add')"
            />
            <q-btn
              v-if="contribution.data.orcid_status === 'on_orcid' || contribution.data.orcid_status === 'pending'"
              flat
              no-caps
              color="negative"
              icon="mdi-minus"
              :label="t('orcid-review-credit.remove')"
              :loading="updating === contribution.data.id"
              :disable="addingAll"
              data-test="orcid-review-remove"
              @click="updateContribution(contribution.data.id, 'remove')"
            />
          </div>
        </q-item-section>
      </q-item>
      <q-item v-if="contributions.data.length === 0">
        <q-item-section class="text-grey-7">
          {{ t('orcid-review-credit.empty') }}
        </q-item-section>
      </q-item>
    </q-list>
  </ContentCard>
</template>

<style scoped></style>
