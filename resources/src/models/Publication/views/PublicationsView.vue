<script setup lang="ts">
import type { PublicationResourceList } from '../Publication'
import { watchThrottled } from '@vueuse/core'
import { computed, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import JournalSelect from '@/models/Journal/components/JournalSelect.vue'
import PublicationList from '../components/PublicationList.vue'
import { PublicationQuery, PublicationService } from '../Publication'

// State variables
const publications = ref<PublicationResourceList>()
const loading = ref(false)
const activeFilter = ref(1)
const currentPage = ref(1)
const search = ref<string | null>(null)
const showFilters = ref(false)
const journalId = ref<number | null>(null)
const journalSelect = ref<InstanceType<typeof JournalSelect> | null>(null)
const authorId = ref<number | null>(null)
const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null)

// i18n
const { t } = useI18n()

// Main filter options
const mainFilterOptions = computed<MainFilterOption[]>(() => {
  return [
    {
      id: 1,
      label: t('my-publication-view.all-publications'),
      caption: t('publications-view.all-published-publications-details'),
      icon: 'mdi-all-inclusive',
      active: activeFilter.value === 1,
      filter: (query: PublicationQuery): PublicationQuery => {
        return query
      },
    },
    {
      id: 2,
      label: t('common.open-access'),
      caption: t('publications-view.open-access-filter-details'),
      icon: 'mdi-lock-open-outline',
      active: activeFilter.value === 2,
      filter: (query: PublicationQuery): PublicationQuery => {
        return query.filterOpenAccess()
      },
    },
    {
      id: 3,
      label: t('publications-view.under-embargo'),
      caption: t('publications-view.under-embargo-caption'),
      icon: 'mdi-calendar-clock-outline',
      active: activeFilter.value === 3,
      filter: (query: PublicationQuery): PublicationQuery => {
        return query.filterUnderEmbargo()
      },
    },
    {
      id: 4,
      label: t('publications-view.secondary-publications'),
      caption: t('publications-view.published-in-dfo-journals'),
      icon: 'mdi-bank-outline',
      active: activeFilter.value === 4,
      filter: (query: PublicationQuery): PublicationQuery => {
        return query.filterSecondaryPublication()
      },
    },
  ]
})

// Computed properties
const mainFilter = computed(() => {
  return mainFilterOptions.value.find(f => f.active)
})

const filterCaption = computed(() => {
  let caption = ''
  if (journalId.value) {
    const { title } = journalSelect?.value?.selectedJournal?.data || {}
    caption += `${t('common.in')} ${title || 'NA'} `
  }
  if (authorId.value) {
    const { first_name, last_name } = authorSelect?.value?.selectedAuthor?.data || {}
    caption += `${t('common.by')} ${first_name || 'NA'} ${last_name || 'NA'} `
  }
  if (caption.length > 0)
    caption = `${t('common.publications')} ${caption.slice(0, -1)}`
  else caption = t('common.no-filters-applied')
  return caption
})

// Methods
async function getPublications() {
  if (loading.value)
    return
  // build the query
  let query = new PublicationQuery()

  // apply the active main filters
  mainFilterOptions.value.forEach((f) => {
    if (f.active) {
      query = f.filter(query)
    }
  })

  // is there a search term?
  if (search?.value) {
    query = query.filterTitle(search.value)
  }

  if (journalId.value) {
    query = query.filterJournalID([journalId.value])
  }

  if (authorId.value) {
    query = query.filterAuthorID([authorId.value])
  }

  query.sort('title', 'asc')
  query.paginate(currentPage.value, 10)

  loading.value = true
  publications.value = await PublicationService.list(query)
  loading.value = false
}

function mainFilterClick(filterId: number) {
  activeFilter.value = filterId
  search.value = ''
  currentPage.value = 1
  getPublications()
}

// Watchers
watch(currentPage, () => {
  getPublications()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    getPublications()
  },
  { throttle: 750 },
)

watch(journalId, () => {
  currentPage.value = 1
  getPublications()
})

watch(authorId, () => {
  currentPage.value = 1
  getPublications()
})

// Lifecycle hooks
onMounted(() => {
  getPublications()
})

// Type definitions
interface MainFilterOption {
  id: number
  label: string
  caption?: string
  icon: string
  active: boolean
  filter: (query: PublicationQuery) => PublicationQuery
}
</script>

<template>
  <MainPageLayout :title="$t('common.publications')">
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="col-3">
        <ContentCard secondary no-padding>
          <template #title>
            {{ $t('common.publications') }}
          </template>
          <q-list class="text-body1">
            <q-item
              v-for="f in mainFilterOptions"
              :key="f.id"
              v-ripple
              clickable
              :active="f.active"
              @click="mainFilterClick(f.id)"
            >
              <q-item-section avatar>
                <q-icon :name="f.icon" />
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ f.label }}</q-item-label>
                <q-item-label caption>
                  {{ f.caption }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </ContentCard>
      </div>
      <div class="col q-pr-lg q-pb-lg">
        <ContentCard secondary>
          <template #title>
            {{ mainFilter?.label }}
          </template>
          <template #subtitle>
            {{ mainFilter?.caption }}
          </template>
          <template #title-right>
            <SearchInput
              v-model="search"
              :label="$t('common.filter')"
            />
          </template>
          <template #nav>
            <q-expansion-item
              v-model="showFilters"
              icon="mdi-filter-variant"
              :label="$t('common.filters')"
              :caption="filterCaption"
            >
              <q-card-section class="column q-gutter-md">
                <JournalSelect
                  ref="journalSelect"
                  v-model="journalId"
                  :label="$t('common.journal')"
                  :disable="loading"
                />
                <AuthorSelect
                  ref="authorSelect"
                  v-model="authorId"
                  :label="$t('common.author')"
                  :disable="loading"
                  :hide-create-author-dialog="true"
                />
              </q-card-section>
            </q-expansion-item>
            <q-separator />
          </template>
          <template v-if="publications?.data.length === 0">
            <NoResultFoundDiv />
          </template>
          <PublicationList :publications="publications?.data ?? []" />
          <q-card-section>
            <q-separator class="q-mb-md" />
            <PaginationDiv
              v-model="currentPage"
              :meta="publications?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
