<script setup lang="ts">
import type {
  ManuscriptRecordSummaryResourceList,
  ManuscriptRecordType,
} from '../ManuscriptRecord'
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import FunctionalAreaSelect from '@/models/FunctionalArea/components/FunctionalAreaSelect.vue'
import RegionSelect from '@/models/Region/components/RegionSelect.vue'
import ManuscriptList from '../components/ManuscriptList.vue'
import ManuscriptTypeSelect from '../components/ManuscriptTypeSelect.vue'
import {
  ManuscriptRecordListQuery,
  ManuscriptRecordService,
} from '../ManuscriptRecord'

const authStore = useAuthStore()

// Check if user has permission (but don't redirect automatically)
const hasPermission = computed(() => {
  // Wait for auth to load before making permission decisions
  if (authStore.loading || !authStore.user)
    return null
  return authStore.canViewRegionalManuscripts
})

// State variables
const manuscripts = ref<ManuscriptRecordSummaryResourceList>()
const loading = ref(false)
const activeFilter = ref(1)
const currentPage = ref(1)
const search = ref<string | null>(null)
const showFilters = ref(false)
const regionId = ref<number | null>(null)
const regionSelect = ref<InstanceType<typeof RegionSelect> | null>(null)
const functionalAreaId = ref<number | null>(null)
const functionalAreaSelect = ref<InstanceType<
  typeof FunctionalAreaSelect
> | null>(null)
const authorId = ref<number | null>(null)
const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null)
const manuscriptType = ref<ManuscriptRecordType | null>(null)

// i18n
const { t } = useI18n()

// Main filter options
const mainFilterOptions = computed<MainFilterOption[]>(() => {
  return [
    {
      id: 1,
      label: t('regional-manuscripts.all-regional-manuscripts'),
      caption: t('regional-manuscripts.all-manuscripts-you-can-view'),
      icon: 'mdi-all-inclusive',
      active: activeFilter.value === 1,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query
      },
    },
    {
      id: 2,
      label: t('regional-manuscripts.under-management-review'),
      caption: t('regional-manuscripts.in-management-review-process'),
      icon: 'mdi-progress-clock',
      active: activeFilter.value === 2,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterStatus(['in_review'])
      },
    },
    {
      id: 3,
      label: t('common.completed'),
      caption: t('regional-manuscripts.accepted-published-manuscripts'),
      icon: 'mdi-check-circle',
      active: activeFilter.value === 3,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterStatus(['accepted', 'withdrawn'])
      },
    },
    {
      id: 4,
      label: t('regional-manuscripts.potential-public-interest'),
      caption: t('regional-manuscripts.manuscripts-with-public-interest'),
      icon: 'mdi-eye-outline',
      active: activeFilter.value === 4,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterPotentialPublicInterest(true)
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
  // For RegionSelect, we need to access the store since it doesn't expose selectedRegion
  if (regionId.value) {
    caption += `${t('common.in')} ${t('common.dfo-region')} `
  }
  // For FunctionalAreaSelect, we need to access the store since it doesn't expose selectedFunctionalArea
  if (functionalAreaId.value) {
    caption += `${t('common.for')} ${t('common.functional-area')} `
  }
  // AuthorSelect properly exposes selectedAuthor
  if (authorId.value) {
    const { first_name, last_name }
      = authorSelect?.value?.selectedAuthor?.data || {}
    caption += `${t('common.by')} ${first_name || 'NA'} ${last_name || 'NA'} `
  }
  // Manuscript type filter
  if (manuscriptType.value) {
    const typeLabel
      = manuscriptType.value === 'primary'
        ? t('manuscript.primary')
        : manuscriptType.value === 'secondary'
          ? t('manuscript.secondary')
          : t('common.preprint')
    caption += `${t('common.type')} ${typeLabel} `
  }
  if (caption.length > 0)
    caption = `${t('common.manuscripts')} ${caption.slice(0, -1)}`
  else caption = t('common.no-filters-applied')
  return caption
})

// Methods
async function getManuscripts() {
  if (loading.value)
    return
  // build the query
  let query = new ManuscriptRecordListQuery()

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

  if (regionId.value) {
    query = query.filterRegionId([regionId.value])
  }

  if (functionalAreaId.value) {
    query = query.filterFunctionalAreaId([functionalAreaId.value])
  }

  if (authorId.value) {
    query = query.filterAuthorId([authorId.value])
  }

  if (manuscriptType.value) {
    query = query.filterType([manuscriptType.value])
  }

  query.sort('updated_at', 'desc')
  query.paginate(currentPage.value, 10)

  loading.value = true
  manuscripts.value = await ManuscriptRecordService.list(query)
  loading.value = false
}

function mainFilterClick(filterId: number) {
  activeFilter.value = filterId
  search.value = ''
  currentPage.value = 1
  getManuscripts()
}

// Watchers
watch(currentPage, () => {
  getManuscripts()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    getManuscripts()
  },
  { throttle: 750 },
)

watch(regionId, () => {
  currentPage.value = 1
  getManuscripts()
})

watch(functionalAreaId, () => {
  currentPage.value = 1
  getManuscripts()
})

watch(authorId, () => {
  currentPage.value = 1
  getManuscripts()
})

watch(manuscriptType, () => {
  currentPage.value = 1
  getManuscripts()
})

// Lifecycle hooks - only load data if user has permission
onMounted(() => {
  if (hasPermission.value) {
    getManuscripts()
  }
})

// Type definitions
interface MainFilterOption {
  id: number
  label: string
  caption?: string
  icon: string
  active: boolean
  filter: (query: ManuscriptRecordListQuery) => ManuscriptRecordListQuery
}
</script>

<template>
  <MainPageLayout :title="$t('common.regional-manuscripts')">
    <div
      class="row q-gutter-lg q-col-gutter-lg flex"
    >
      <div class="col-3">
        <ContentCard secondary no-padding>
          <template #title>
            {{ $t('common.regional-manuscripts') }}
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
            <SearchInput v-model="search" :label="$t('common.filter')" />
          </template>
          <template #nav>
            <q-expansion-item
              v-model="showFilters"
              icon="mdi-filter-variant"
              :label="$t('common.filters')"
              :caption="filterCaption"
            >
              <q-card-section class="column q-gutter-md">
                <RegionSelect
                  ref="regionSelect"
                  v-model="regionId"
                  clearable
                  :disable="loading"
                />
                <FunctionalAreaSelect
                  ref="functionalAreaSelect"
                  v-model="functionalAreaId"
                  clearable
                  :label="$t('common.functional-area')"
                  :disable="loading"
                />
                <AuthorSelect
                  ref="authorSelect"
                  v-model="authorId"
                  :label="$t('common.author')"
                  :disable="loading"
                  :hide-create-author-dialog="true"
                />
                <ManuscriptTypeSelect
                  v-model="manuscriptType"
                  clearable
                />
              </q-card-section>
            </q-expansion-item>
            <q-separator />
          </template>
          <template v-if="manuscripts?.data.length === 0">
            <NoResultFoundDiv />
          </template>
          <ManuscriptList :manuscripts="manuscripts?.data ?? []" />
          <q-card-section>
            <q-separator class="q-mb-md" />
            <PaginationDiv
              v-model="currentPage"
              :meta="manuscripts?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
