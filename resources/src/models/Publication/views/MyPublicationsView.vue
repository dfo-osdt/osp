<script setup lang="ts">
import type {
  PublicationResourceList,
} from '../Publication'
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import CreatePublicationDialog from '../components/CreatePublicationDialog.vue'
import NoPublicationsExistDiv from '../components/NoPublicationExistDiv.vue'
import PublicationList from '../components/PublicationList.vue'
import {
  PublicationQuery,
  PublicationService,
} from '../Publication'

const { t } = useI18n()

const publications = ref<PublicationResourceList>()
const loading = ref(false)
const showCreatePublicationDialog = ref(false)
const authorStore = useAuthStore()
const publicationStore = usePublicationStore()
const activeFilterId = ref(2)
const currentPage = ref(1)
const search = ref<string | null>(null)

publicationStore.getMyPublications()

const hasAnyPublications = computed(() => {
  if (!publicationStore.publications)
    return false
  return publicationStore.publications?.length > 0
})

interface MainFilterOption {
  id: number
  label: string
  caption?: string
  icon: string
  active: boolean
  filter: (query: PublicationQuery) => PublicationQuery
}

const mainFilterOptions = computed((): MainFilterOption[] => [
  {
    id: 1,
    label: t('my-publication-view.all-publications'),
    caption: t('my-publication-view.includes-ones-shared-with-me'),
    icon: 'mdi-all-inclusive',
    active: activeFilterId.value === 1,
    filter: (query: PublicationQuery): PublicationQuery => {
      return query
    },
  },
  {
    id: 2,
    label: t('my-publication-view.my-publications'),
    caption: t(
      'my-publication-view.i-am-responsible-for-this-publication-record',
    ),
    icon: 'mdi-account-arrow-left-outline',
    active: activeFilterId.value === 2,
    filter: (query: PublicationQuery): PublicationQuery => {
      return authorStore.user
        ? query.filterUserId([authorStore.user.id])
        : query
    },
  },
  {
    id: 3,
    label: t('common.in-progress'),
    caption: t('my-publication-view.actions-still-required'),
    icon: 'mdi-progress-clock',
    active: activeFilterId.value === 3,
    filter: (query: PublicationQuery): PublicationQuery => {
      return query.filterStatus(['accepted'])
    },
  },
  {
    id: 4,
    label: t('publication.published'),
    caption: t('common.no-actions-required'),
    icon: 'mdi-check-circle',
    active: activeFilterId.value === 4,
    filter: (query: PublicationQuery): PublicationQuery => {
      return query.filterStatus(['published'])
    },
  },
])

const mainFilter = computed(() => {
  return mainFilterOptions.value.find(f => f.active)
})

onMounted(() => {
  getPublications()
})

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

  query.sort('title', 'asc')
  query.paginate(currentPage.value, 5)

  loading.value = true
  publications.value = await PublicationService.getMyPublications(query)
  loading.value = false
}

function mainFilterClick(filterId: number) {
  activeFilterId.value = filterId
  search.value = ''
  currentPage.value = 1
  getPublications()
}

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
</script>

<template>
  <MainPageLayout :title="$t('common.my-publications')">
    <template v-if="authorStore.user?.can('create_publications')" #toolbar>
      <div class="flex justify-end">
        <q-fab
          v-model="showCreatePublicationDialog"
          color="primary"
          icon="mdi-plus"
          padding="md"
          class="absolute-top-right q-mr-lg q-mt-lg"
        >
          <template #tooltip>
            <q-tooltip
              class="text-body2"
              anchor="center left"
              self="center right"
            >
              {{
                $t('my-publication-view.create-publication')
              }}
            </q-tooltip>
          </template>
        </q-fab>
      </div>
    </template>
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
          <div
            v-if="authorStore.user?.can('create_publications')"
            class="flex-center flex q-mt-lg q-mb-sm q-mx-md"
          >
            <q-btn
              :label="
                $t('my-publication-view.create-publication')
              "
              color="primary"
              outline
              icon="mdi-plus"
              @click="showCreatePublicationDialog = true"
            />
          </div>
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
            <SearchInput
              v-model="search"
              :label="$t('common.filter')"
            />
          </template>
          <template
            v-if="
              publications?.data.length === 0
                && !hasAnyPublications
            "
          >
            <NoPublicationsExistDiv
              :title="
                $t('my-publication-view.no-publications-found')
              "
            />
          </template>
          <template
            v-if="
              publications?.data.length === 0
                && hasAnyPublications
            "
          >
            <NoResultFoundDiv />
          </template>
          <PublicationList :publications="publications?.data ?? []" />
          <q-card-section>
            <PaginationDiv
              v-model="currentPage"
              :meta="publications?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
    <CreatePublicationDialog
      v-model="showCreatePublicationDialog"
    />
  </MainPageLayout>
</template>

<style scoped></style>
