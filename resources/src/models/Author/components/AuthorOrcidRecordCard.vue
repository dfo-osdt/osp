<script setup lang="ts">
import type { AuthorOrcidAffiliation, AuthorOrcidRecord, AuthorResource } from '../Author'
import ContentCard from '@/components/ContentCard.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'
import { AuthorService } from '../Author'

const props = defineProps<{
  author: AuthorResource
}>()

const { t } = useI18n()
const authStore = useAuthStore()
const localeStore = useLocaleStore()

const orcidRecord = ref<AuthorOrcidRecord | null>(null)
const loading = ref(false)
const expanded = ref(false)
const isClamped = ref(false)
const biographyElement = ref<HTMLElement | null>(null)

const isOwnProfile = computed(() => authStore.user?.author.data.id === props.author.data.id)
const shouldLoad = computed(() => props.author.data.orcid_connected && props.author.data.show_orcid_record)
const biography = computed(() => orcidRecord.value?.record?.biography ?? null)
const employments = computed(() => orcidRecord.value?.record?.employments ?? [])
const educations = computed(() => orcidRecord.value?.record?.educations ?? [])
const peerReviewCount = computed(() => orcidRecord.value?.record?.peer_review_count ?? 0)
const isUnavailable = computed(() => orcidRecord.value?.status === 'unavailable')

const hasPublicContent = computed(() => biography.value !== null
  || employments.value.length > 0
  || educations.value.length > 0
  || peerReviewCount.value > 0)

const affiliationSections = computed(() => [
  {
    key: 'employment',
    label: t('author-profile.orcid-employment'),
    icon: 'mdi-briefcase-outline',
    affiliations: employments.value,
  },
  {
    key: 'education',
    label: t('author-profile.orcid-education'),
    icon: 'mdi-school-outline',
    affiliations: educations.value,
  },
].filter(section => section.affiliations.length > 0))

// the author chose to show their record but is not (or no longer) connected to ORCID
const showConnectPrompt = computed(() => isOwnProfile.value
  && props.author.data.show_orcid_record
  && !loading.value
  && (!props.author.data.orcid_connected || orcidRecord.value?.status === 'empty'))

const showNothingPublicHint = computed(() => isOwnProfile.value
  && orcidRecord.value?.status === 'available'
  && !hasPublicContent.value)

const isVisible = computed(() => loading.value
  || isUnavailable.value
  || hasPublicContent.value
  || showConnectPrompt.value
  || showNothingPublicHint.value)

async function loadOrcidRecord() {
  orcidRecord.value = null
  expanded.value = false
  if (!shouldLoad.value)
    return

  loading.value = true
  try {
    orcidRecord.value = await AuthorService.getOrcidRecord(props.author.data.id)
  }
  catch {
    orcidRecord.value = { status: 'unavailable', record: null }
  }
  finally {
    loading.value = false
  }
}

/**
 * An ORCID fuzzy date ("2021", "2021-05" or "2021-05-14") as a year, or a month and year
 */
function formatFuzzyDate(date: string) {
  const [year, month] = date.split('-').map(Number)
  if (!month)
    return String(year)

  return new Date(Date.UTC(year, month - 1)).toLocaleDateString(`${localeStore.locale}-CA`, {
    year: 'numeric',
    month: 'short',
    timeZone: 'UTC',
  })
}

// as on the author's employments: "start - end", "present" when there is no end date
function dateRange(affiliation: AuthorOrcidAffiliation) {
  if (!affiliation.start_date) {
    return affiliation.end_date
      ? formatFuzzyDate(affiliation.end_date)
      : null
  }

  const end = affiliation.end_date
    ? formatFuzzyDate(affiliation.end_date)
    : t('common.present')

  return `${formatFuzzyDate(affiliation.start_date)} - ${end}`
}

function roleAndDepartment(affiliation: AuthorOrcidAffiliation) {
  return [affiliation.role, affiliation.department].filter(Boolean).join(', ')
}

// only offer "Read more" when the biography is longer than the clamped lines
function checkClamped() {
  const element = biographyElement.value
  isClamped.value = element !== null && element.scrollHeight > element.clientHeight
}

useResizeObserver(biographyElement, () => {
  if (!expanded.value)
    checkClamped()
})

onMounted(loadOrcidRecord)
watch(() => [props.author.data.id, shouldLoad.value], loadOrcidRecord)
</script>

<template>
  <ContentCard v-if="isVisible" secondary>
    <template #title>
      <OrcidIcon size="sm" class="q-mr-sm" />
      {{ t('author-profile.orcid-record') }}
    </template>
    <template v-if="hasPublicContent && !loading" #title-right>
      <q-btn
        icon="mdi-open-in-new"
        flat
        round
        dense
        color="secondary"
        :href="author.data.orcid"
        target="_blank"
        rel="noopener noreferrer"
        :aria-label="t('author-profile.orcid-view-record')"
      >
        <q-tooltip>{{ t('author-profile.orcid-view-record') }}</q-tooltip>
      </q-btn>
    </template>

    <template v-if="loading">
      <q-skeleton type="text" />
      <q-skeleton type="text" />
      <q-skeleton type="text" width="60%" />
    </template>

    <div v-else-if="isUnavailable" class="text-grey-7">
      {{ t('author-profile.orcid-unavailable') }}
    </div>

    <template v-else-if="hasPublicContent">
      <section v-if="biography" class="q-mb-md">
        <div class="flex items-center q-mb-sm">
          <q-icon name="mdi-text-account" size="sm" color="grey-6" class="q-mr-sm" />
          <span class="text-subtitle2">{{ t('author-profile.orcid-biography') }}</span>
        </div>
        <!-- plain text from ORCID: interpolated (escaped), never rendered as HTML -->
        <div
          ref="biographyElement"
          class="text-body2 orcid-text orcid-biography"
          :class="{ 'orcid-biography--clamped': !expanded }"
        >
          {{ biography }}
        </div>
        <q-btn
          v-if="isClamped || expanded"
          flat
          dense
          no-caps
          color="primary"
          class="q-mt-sm"
          :label="expanded ? t('author-profile.read-less') : t('author-profile.read-more')"
          @click="expanded = !expanded"
        />
      </section>

      <section v-for="section in affiliationSections" :key="section.key" class="q-mb-md">
        <div class="flex items-center q-mb-sm">
          <q-icon :name="section.icon" size="sm" color="grey-6" class="q-mr-sm" />
          <span class="text-subtitle2">{{ section.label }}</span>
        </div>
        <q-list bordered separator class="rounded-borders">
          <q-item v-for="(affiliation, index) in section.affiliations" :key="index">
            <q-item-section>
              <q-item-label overline class="orcid-text">
                {{ affiliation.organization ?? '-' }}
              </q-item-label>
              <q-item-label v-if="affiliation.ror" caption>
                <RORLinkSpan :ror="affiliation.ror" />
              </q-item-label>
              <q-item-label v-if="roleAndDepartment(affiliation)" caption class="orcid-text">
                {{ roleAndDepartment(affiliation) }}
              </q-item-label>
              <q-item-label v-if="dateRange(affiliation)">
                {{ dateRange(affiliation) }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </section>

      <div v-if="peerReviewCount > 0" class="flex items-center q-mb-md">
        <q-icon name="mdi-file-document-check-outline" size="sm" color="grey-6" class="q-mr-sm" />
        <span class="text-body1">{{ t('author-profile.orcid-peer-reviews-count', peerReviewCount) }}</span>
      </div>

      <div class="text-caption text-grey-7">
        {{ t('author-profile.orcid-source') }}
        <a
          :href="author.data.orcid"
          target="_blank"
          rel="noopener noreferrer"
          class="text-primary"
        >{{ t('author-profile.orcid-record') }}<q-icon name="mdi-open-in-new" class="q-ml-xs" /></a>
      </div>
    </template>

    <div v-else-if="showConnectPrompt" class="text-grey-7">
      <p>{{ t('author-profile.orcid-connect-prompt') }}</p>
      <q-btn
        outline
        no-caps
        color="primary"
        :to="{ name: 'settings.author' }"
        :label="t('author-profile.orcid-connect-action')"
      />
    </div>

    <div v-else-if="showNothingPublicHint" class="text-grey-7">
      {{ t('author-profile.orcid-nothing-public') }}
    </div>
  </ContentCard>
</template>

<style scoped>
.orcid-text {
  overflow-wrap: anywhere;
}

.orcid-biography {
  white-space: pre-line;
}

.orcid-biography--clamped {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 6;
  line-clamp: 6;
  overflow: hidden;
}
</style>
