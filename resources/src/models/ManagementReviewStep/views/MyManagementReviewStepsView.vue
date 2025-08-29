<script setup lang="ts">
import type { ManagementReviewStepResourceList } from '../ManagementReviewStep'
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import ManagementReviewStepList from '../components/ManagementReviewStepList.vue'
import NoManagementStepExistsDiv from '../components/NoManagementStepExistsDiv.vue'

import {
  ManagementReviewQuery,
  ManagementReviewStepService,
} from '../ManagementReviewStep'

const { t } = useI18n()

const reviews = ref<ManagementReviewStepResourceList>()

// type for main filter options
interface MainFilterOption {
  id: number
  label: string
  caption?: string
  icon: string
  active: boolean
  filter: (query: ManagementReviewQuery) => ManagementReviewQuery
}

// content filter - sidebar
const activeFilterId = ref(2)
const mainFilterOptions = computed<MainFilterOption[]>(() => {
  return [
    {
      id: 1,
      label: t('review-step-view.all-my-reviews'),
      caption: t('review-step-view.list-of-all-my-reviews'),
      icon: 'mdi-all-inclusive',
      active: activeFilterId.value === 1,
      filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
        return query
      },
    },
    {
      id: 2,
      label: t('common.pending'),
      caption: t('common.i-need-to-take-action'),
      icon: 'mdi-progress-clock',
      active: activeFilterId.value === 2,
      filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
        return query.filterStatus(['pending'])
      },
    },
    {
      id: 3,
      label: t('common.completed'),
      caption: t('common.no-actions-required'),
      icon: 'mdi-check-circle',
      active: activeFilterId.value === 3,
      filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
        return query.filterStatus(['deferred', 'completed'])
      },
    },
  ]
})

// pagination
const currentPage = ref(1)

// search
const search = ref<string | null>(null)

onMounted(() => {
  getReviews()
})

const loading = ref(false)
async function getReviews() {
  if (loading.value)
    return
  // build the query
  let query = new ManagementReviewQuery()

  // apply the active main filters
  mainFilterOptions.value.forEach((f) => {
    if (f.active) {
      query = f.filter(query)
    }
  })

  // is there a search term?
  if (search?.value) {
    query = query.filterManuscriptRecordTitle([search.value])
  }

  query.sort('updated_at', 'asc')
  query.paginate(currentPage.value, 5)

  loading.value = true
  reviews.value = await ManagementReviewStepService.listMy(query)
  loading.value = false
}

// manuscript store - used to determine if the user has any reviews
const managementReviewStore = useManagementReviewStepStore()
managementReviewStore.getMyManagementReviewSteps()
const hasAnyManagementSteps = computed(() => {
  if (!managementReviewStore.recent)
    return false
  return managementReviewStore.recent?.length > 0
})

function mainFilterClick(filterId: number) {
  activeFilterId.value = filterId
  search.value = ''
  currentPage.value = 1
  getReviews()
}

const mainFilter = computed(() => {
  return mainFilterOptions.value.find(f => f.active)
})

watch(currentPage, () => {
  getReviews()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    getReviews()
  },
  { throttle: 750 },
)
</script>

<template>
  <MainPageLayout :title="$t('common.my-reviews')">
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="col-3">
        <ContentCard secondary no-padding>
          <template #title>
            {{ $t('common.reviews') }}
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
      <div class="col q-pr-lg">
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
          <template v-if="reviews?.data.length === 0 && !hasAnyManagementSteps">
            <NoManagementStepExistsDiv />
          </template>
          <template v-if="reviews?.data.length === 0 && hasAnyManagementSteps">
            <NoResultFoundDiv />
          </template>
          <ManagementReviewStepList
            v-if="reviews?.data.length"
            :management-review-steps="reviews?.data ?? []"
            :loading="loading"
          />
          <q-card-section>
            <PaginationDiv
              v-model="currentPage"
              :meta="reviews?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
