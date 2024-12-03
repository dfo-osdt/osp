<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { watchThrottled } from '@vueuse/core'
import { computed, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { AuthorQuery, type AuthorResourceList, AuthorService } from '../Author'
import AuthorList from '../components/AuthorList.vue'

// State variables
const authors = ref<AuthorResourceList>()
const loading = ref(false)
const activeFilter = ref(1)
const currentPage = ref(1)
const search = ref<string | null>(null)
const showFilters = ref(false)
const organizationId = ref<number | null>(null)

// i18n
const { t } = useI18n()

// Main filter options
const mainFilterOptions = computed<MainFilterOption[]>(() => {
  return [
    {
      id: 1,
      label: t('authors-view.all-authors'),
      caption: t('authors-view.all-authors-caption'),
      icon: 'mdi-all-inclusive',
      active: activeFilter.value === 1,
      filter: (query: AuthorQuery): AuthorQuery => {
        return query
      },
    },
    {
      id: 2,
      label: t('authors-view.dfo-authors'),
      caption: t('authors-view.dfo-authors-caption'),
      icon: 'mdi-account-arrow-left-outline',
      active: activeFilter.value === 2,
      filter: (query: AuthorQuery): AuthorQuery => {
        return query.filterInternalAuthor()
      },
    },
    {
      id: 3,
      label: t('authors-view.external-authors'),
      caption: t('authors-view.external-authors-caption'),
      icon: 'mdi-account-arrow-right-outline',
      active: activeFilter.value === 3,
      filter: (query: AuthorQuery): AuthorQuery => {
        return query.filterExternalAuthor()
      },
    },
    {
      id: 4,
      label: t('authors-view.with-orcid'),
      caption: t('authors-view.with-orcid-caption'),
      icon: 'mdi-account-school-outline',
      active: activeFilter.value === 4,
      filter: (query: AuthorQuery): AuthorQuery => {
        return query.filterWithOrcid()
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
  if (caption.length > 0)
    caption = `${t('common.authors')} ${caption.slice(0, -1)}`
  else caption = t('common.no-filters-applied')
  return caption
})

// Methods
async function getAuthors() {
  if (loading.value)
    return
  // build the query
  let query = new AuthorQuery()

  // apply the active main filters
  mainFilterOptions.value.forEach((f) => {
    if (f.active) {
      query = f.filter(query)
    }
  })

  // is there a search term?
  if (search?.value) {
    query = query.filterSearch(search.value)
  }

  if (organizationId.value) {
    query = query.filterOrganizationId(organizationId.value)
  }

  query.sort('last_name', 'asc')
  query.include('organization')
  query.paginate(currentPage.value, 10)

  loading.value = true
  authors.value = await AuthorService.list(query)
  loading.value = false
}

function mainFilterClick(filterId: number) {
  activeFilter.value = filterId
  search.value = ''
  organizationId.value = null
  currentPage.value = 1
  getAuthors()
}

// Watchers
watch(currentPage, () => {
  getAuthors()
})

watch(organizationId, () => {
  currentPage.value = 1
  getAuthors()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    getAuthors()
  },
  { throttle: 750 },
)

// Lifecycle hooks
onMounted(() => {
  getAuthors()
})

// Type definitions
interface MainFilterOption {
  id: number
  label: string
  caption?: string
  icon: string
  active: boolean
  filter: (query: AuthorQuery) => AuthorQuery
}
</script>

<template>
  <MainPageLayout :title="$t('common.authors')">
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="col-3">
        <ContentCard secondary no-padding>
          <template #title>
            {{ $t('common.authors') }}
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
                <OrganizationSelect
                  v-model="organizationId"
                  :label="$t('common.organization')"
                />
              </q-card-section>
            </q-expansion-item>
            <q-separator />
          </template>
          <template v-if="authors?.data.length === 0">
            <NoResultFoundDiv />
          </template>
          <AuthorList :authors="authors?.data ?? []" />
          <q-card-section>
            <q-separator class="q-mb-md" />
            <PaginationDiv
              v-model="currentPage"
              :meta="authors?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
