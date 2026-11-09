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
const updatingAction = ref<'add' | 'remove' | null>(null)
const addingAll = ref(false)

type SectionKey = 'undecided' | 'added' | 'declined'

// grouped by the author's decision, so each row only needs the actions that change it
const sectionStatuses: Record<SectionKey, OrcidReviewStatus[]> = {
  undecided: ['not_on_orcid'],
  added: ['pending', 'on_orcid'],
  declined: ['removing', 'declined'],
}

const sections = computed(() => {
  const data = contributions.value?.data ?? []
  return (Object.keys(sectionStatuses) as SectionKey[])
    .map(key => ({
      key,
      contributions: data.filter(contribution => sectionStatuses[key].includes(contribution.data.orcid_status)),
    }))
    .filter(section => section.contributions.length > 0)
})

function sectionTitle(key: SectionKey, count: number): string {
  const titles: Record<SectionKey, string> = {
    undecided: t('orcid-review-credit.sections.undecided', { count }),
    added: t('orcid-review-credit.sections.added', { count }),
    declined: t('orcid-review-credit.sections.declined', { count }),
  }
  return titles[key]
}

// the section already says what was decided: only an outcome worth confirming gets a badge
function hasStatusBadge(status: OrcidReviewStatus): boolean {
  return status !== 'not_on_orcid' && status !== 'declined'
}

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

// declining an undecided contribution uses remove: it is never added unless the author asks
async function updateContribution(id: number, action: 'add' | 'remove', errorMessage: string) {
  updating.value = id
  updatingAction.value = action
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
      message: errorMessage,
    })
  }
  finally {
    updating.value = null
    updatingAction.value = null
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
    <q-list v-if="contributions" bordered class="rounded-borders">
      <template v-for="(section, sectionIndex) in sections" :key="section.key">
        <q-separator v-if="sectionIndex > 0" />
        <q-item class="bg-grey-1" :data-test="`orcid-review-section-${section.key}`">
          <q-item-section>
            <q-item-label class="text-subtitle2 text-grey-9">
              {{ sectionTitle(section.key, section.contributions.length) }}
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="section.key === 'undecided' && section.contributions.length > 1" side>
            <q-btn
              unelevated
              no-caps
              dense
              color="primary"
              icon="mdi-playlist-plus"
              class="q-px-sm"
              :label="t('orcid-review-credit.add-all')"
              :loading="addingAll"
              :disable="updating !== null"
              data-test="orcid-review-add-all"
              @click="addAllToOrcid"
            />
          </q-item-section>
        </q-item>
        <template v-for="contribution in section.contributions" :key="contribution.data.id">
          <q-separator inset />
          <q-item>
            <q-item-section>
              <q-item-label class="text-weight-medium">
                <TitleText :title="contribution.data.publication.title" />
              </q-item-label>
              <q-item-label caption>
                {{ t('orcid-review-credit.journal-and-role', { journal: contribution.data.publication.journal.data.title, role: roleLabel(contribution.data.role) }) }}
              </q-item-label>
              <q-item-label v-if="hasStatusBadge(contribution.data.orcid_status)">
                <q-badge
                  outline
                  :color="statusColor(contribution.data.orcid_status)"
                  :label="statusLabel(contribution.data.orcid_status)"
                >
                  <q-icon v-if="contribution.data.orcid_status === 'on_orcid'" name="mdi-check" class="q-ml-xs" />
                  <q-tooltip v-if="contribution.data.orcid_status === 'removing' && isWaitingForConnection(contribution.data.orcid_status)">
                    {{ t('orcid-review-credit.removing-hint') }}
                  </q-tooltip>
                </q-badge>
              </q-item-label>
            </q-item-section>
            <q-item-section side>
              <div class="row items-center no-wrap q-gutter-xs">
                <q-btn
                  v-if="section.key === 'undecided'"
                  flat
                  no-caps
                  dense
                  color="grey-8"
                  class="q-px-sm"
                  :label="t('orcid-review-credit.decline')"
                  :loading="updating === contribution.data.id && updatingAction === 'remove'"
                  :disable="addingAll || updating === contribution.data.id"
                  data-test="orcid-review-decline"
                  @click="updateContribution(contribution.data.id, 'remove', t('orcid-review-credit.decline-error'))"
                />
                <q-btn
                  v-if="section.key !== 'added'"
                  :outline="section.key === 'undecided'"
                  :flat="section.key === 'declined'"
                  no-caps
                  dense
                  color="primary"
                  icon="mdi-plus"
                  class="q-px-sm"
                  :label="t('orcid-review-credit.add')"
                  :loading="updating === contribution.data.id && updatingAction === 'add'"
                  :disable="addingAll || updating === contribution.data.id"
                  data-test="orcid-review-add"
                  @click="updateContribution(contribution.data.id, 'add', t('orcid-review-credit.add-error'))"
                />
                <q-btn
                  v-if="section.key === 'added'"
                  flat
                  no-caps
                  dense
                  color="negative"
                  class="q-px-sm"
                  :label="t('orcid-review-credit.remove')"
                  :loading="updating === contribution.data.id"
                  :disable="addingAll"
                  data-test="orcid-review-remove"
                  @click="updateContribution(contribution.data.id, 'remove', t('orcid-review-credit.remove-error'))"
                />
              </div>
            </q-item-section>
          </q-item>
        </template>
      </template>
      <q-item v-if="contributions.data.length === 0">
        <q-item-section class="text-grey-7">
          {{ t('orcid-review-credit.empty') }}
        </q-item-section>
      </q-item>
    </q-list>
  </ContentCard>
</template>

<style scoped></style>
