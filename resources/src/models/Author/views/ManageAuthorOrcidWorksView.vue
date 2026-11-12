<script setup lang="ts">
import type { OrcidWorkResourceList, OrcidWorkStatus } from '@/models/PublicationAuthor/OrcidWork'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import TitleText from '@/components/TitleText.vue'
import { OrcidWorkService } from '@/models/PublicationAuthor/OrcidWork'

const { t } = useI18n()
const q = useQuasar()
const authStore = useAuthStore()
const localeStore = useLocaleStore()

// a plain link, not an API call: pass the locale so ORCID shows the same language
const connectUrl = computed(() => `/api/user/orcid/verify?locale=${localeStore.locale}`)

const hasAuthor = computed(() => !!authStore.user?.author?.data)

const works = ref<OrcidWorkResourceList | null>(null)
const loading = ref(false)
const updating = ref<number | null>(null)
const updatingAction = ref<'add' | 'remove' | null>(null)
const addingAll = ref(false)

type SectionKey = 'undecided' | 'added' | 'declined'

// grouped by the author's decision, so each row only needs the actions that change it
const sectionStatuses: Record<SectionKey, OrcidWorkStatus[]> = {
  undecided: ['not_on_orcid'],
  added: ['pending', 'on_orcid'],
  declined: ['removing', 'declined'],
}

const sections = computed(() => {
  const data = works.value?.data ?? []
  return (Object.keys(sectionStatuses) as SectionKey[])
    .map(key => ({
      key,
      works: data.filter(work => sectionStatuses[key].includes(work.data.orcid_status)),
    }))
    .filter(section => section.works.length > 0)
})

function sectionTitle(key: SectionKey, count: number): string {
  const titles: Record<SectionKey, string> = {
    undecided: t('orcid-works.sections.undecided', { count }),
    added: t('orcid-works.sections.added', { count }),
    declined: t('orcid-works.sections.declined', { count }),
  }
  return titles[key]
}

// the section already says what was decided: only an outcome worth confirming gets a badge
function hasStatusBadge(status: OrcidWorkStatus): boolean {
  return status !== 'not_on_orcid' && status !== 'declined'
}

// additions and removals in progress run in the background
function isInProgress(status: OrcidWorkStatus): boolean {
  return status === 'pending' || status === 'removing'
}

// works in progress wait for the author to connect ORCID with the write scope
function isWaitingForConnection(status: OrcidWorkStatus): boolean {
  return isInProgress(status) && !works.value?.meta.can_write_to_orcid
}

function statusLabel(status: OrcidWorkStatus): string {
  if (isWaitingForConnection(status))
    return t('orcid-works.status.waiting_for_connection')

  const labels: Record<OrcidWorkStatus, string> = {
    not_on_orcid: t('orcid-works.status.not_on_orcid'),
    pending: t('orcid-works.status.pending'),
    on_orcid: t('orcid-works.status.on_orcid'),
    removing: t('orcid-works.status.removing'),
    declined: t('orcid-works.status.declined'),
  }
  return labels[status]
}

function statusColor(status: OrcidWorkStatus): string {
  return status === 'on_orcid'
    ? 'positive'
    : 'grey-8'
}

function publicationYear(publishedOn: string | null): string {
  return publishedOn?.slice(0, 4) ?? ''
}

// works being added or removed are synced in the background: reload until ORCID
// is up to date, for a minute at most
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
  const hasInProgress = works.value?.meta.can_write_to_orcid
    && works.value.data.some(work => isInProgress(work.data.orcid_status))
  if (!hasInProgress || pollCount >= maxPolls)
    return

  pollTimeoutId = setTimeout(async () => {
    pollCount++
    try {
      works.value = await OrcidWorkService.list()
      pollWhileInProgress()
    }
    catch {
      // a failed background reload is not worth an error: the page still shows the last list
    }
  }, pollIntervalMs)
}

// declining an undecided publication uses remove: it is never added unless the author asks
async function updateWork(id: number, action: 'add' | 'remove', errorMessage: string) {
  updating.value = id
  updatingAction.value = action
  try {
    const updated = await OrcidWorkService[action](id)
    if (!works.value)
      return
    const index = works.value.data.findIndex(work => work.data.id === id)
    if (index !== -1)
      works.value.data[index] = updated
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
    works.value = await OrcidWorkService.addAll()
    pollCount = 0
    pollWhileInProgress()
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-works.add-all-error'),
    })
  }
  finally {
    addingAll.value = false
  }
}

const savingAutoSync = ref(false)

async function updateAutoSync(autoSync: boolean) {
  if (!works.value)
    return

  savingAutoSync.value = true
  try {
    const response = await OrcidWorkService.updateAutoSync(autoSync)
    works.value.meta.auto_sync = response.auto_sync
    q.notify({
      type: 'positive',
      message: autoSync
        ? t('orcid-works.auto-sync-on')
        : t('orcid-works.auto-sync-off'),
    })
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-works.auto-sync-error'),
    })
  }
  finally {
    savingAutoSync.value = false
  }
}

async function loadWorks() {
  loading.value = true
  try {
    works.value = await OrcidWorkService.list()
    pollWhileInProgress()
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-works.load-error'),
    })
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  if (hasAuthor.value)
    loadWorks()
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
    :label="t('orcid-works.back')"
    :to="{ name: 'settings.author' }"
  />
  <ContentCard v-if="hasAuthor" secondary class="q-mb-xl" data-test="orcid-works">
    <template #title>
      <q-icon name="mdi-file-document-check-outline" size="md" />
      <span class="q-pl-sm">{{ t('orcid-works.title') }}</span>
    </template>
    <template #subtitle>
      {{ t('orcid-works.intro') }}
    </template>
    <q-banner
      v-if="works && !works.meta.can_write_to_orcid"
      class="bg-blue-grey-1 text-grey-9 q-mb-md"
      rounded
      dense
      inline-actions
      data-test="orcid-connect-banner"
    >
      <template #avatar>
        <q-icon name="mdi-information-outline" color="primary" />
      </template>
      {{ t('orcid-works.connect-banner') }}
      <template #action>
        <q-btn outline no-caps color="primary" :href="connectUrl">
          <OrcidAvatar size="sm" />
          <span class="q-ml-sm">{{ t('orcid.verify-btn-text') }}</span>
        </q-btn>
      </template>
    </q-banner>
    <q-inner-loading :showing="loading" />
    <q-list v-if="works" bordered class="rounded-borders q-mb-md">
      <q-item>
        <q-item-section avatar top>
          <q-icon name="mdi-autorenew" color="primary" />
        </q-item-section>
        <q-item-section>
          <q-item-label>{{ t('orcid-works.auto-sync') }}</q-item-label>
          <q-item-label caption>
            {{ t('orcid-works.auto-sync-hint') }}
          </q-item-label>
        </q-item-section>
        <q-item-section side top>
          <q-toggle
            :model-value="works.meta.auto_sync"
            :aria-label="t('orcid-works.auto-sync')"
            :disable="savingAutoSync"
            data-test="orcid-works-auto-sync"
            @update:model-value="updateAutoSync"
          />
        </q-item-section>
      </q-item>
    </q-list>
    <q-list v-if="works" bordered class="rounded-borders">
      <template v-for="(section, sectionIndex) in sections" :key="section.key">
        <q-separator v-if="sectionIndex > 0" />
        <q-item class="bg-grey-1" :data-test="`orcid-works-section-${section.key}`">
          <q-item-section>
            <q-item-label class="text-subtitle2 text-grey-9">
              {{ sectionTitle(section.key, section.works.length) }}
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="section.key === 'undecided' && section.works.length > 1" side>
            <q-btn
              unelevated
              no-caps
              dense
              color="primary"
              icon="mdi-playlist-plus"
              class="q-px-sm"
              :label="t('orcid-works.add-all')"
              :loading="addingAll"
              :disable="updating !== null"
              data-test="orcid-works-add-all"
              @click="addAllToOrcid"
            />
          </q-item-section>
        </q-item>
        <template v-for="work in section.works" :key="work.data.id">
          <q-separator inset />
          <q-item>
            <q-item-section>
              <q-item-label class="text-weight-medium">
                <TitleText :title="work.data.publication.title" />
              </q-item-label>
              <q-item-label caption>
                {{ t('orcid-works.journal-and-year', { journal: work.data.publication.journal.data.title, year: publicationYear(work.data.publication.published_on) }) }}
              </q-item-label>
              <q-item-label v-if="hasStatusBadge(work.data.orcid_status)">
                <q-badge
                  outline
                  :color="statusColor(work.data.orcid_status)"
                  :label="statusLabel(work.data.orcid_status)"
                >
                  <q-icon v-if="work.data.orcid_status === 'on_orcid'" name="mdi-check" class="q-ml-xs" />
                  <q-tooltip v-if="work.data.orcid_status === 'removing' && isWaitingForConnection(work.data.orcid_status)">
                    {{ t('orcid-works.removing-hint') }}
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
                  :label="t('orcid-works.decline')"
                  :loading="updating === work.data.id && updatingAction === 'remove'"
                  :disable="addingAll || updating === work.data.id"
                  data-test="orcid-works-decline"
                  @click="updateWork(work.data.id, 'remove', t('orcid-works.decline-error'))"
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
                  :label="t('orcid-works.add')"
                  :loading="updating === work.data.id && updatingAction === 'add'"
                  :disable="addingAll || updating === work.data.id"
                  data-test="orcid-works-add"
                  @click="updateWork(work.data.id, 'add', t('orcid-works.add-error'))"
                />
                <q-btn
                  v-if="section.key === 'added'"
                  flat
                  no-caps
                  dense
                  color="negative"
                  class="q-px-sm"
                  :label="t('orcid-works.remove')"
                  :loading="updating === work.data.id"
                  :disable="addingAll"
                  data-test="orcid-works-remove"
                  @click="updateWork(work.data.id, 'remove', t('orcid-works.remove-error'))"
                />
              </div>
            </q-item-section>
          </q-item>
        </template>
      </template>
      <q-item v-if="works.data.length === 0">
        <q-item-section class="text-grey-7">
          {{ t('orcid-works.empty') }}
        </q-item-section>
      </q-item>
    </q-list>
  </ContentCard>
</template>

<style scoped></style>
