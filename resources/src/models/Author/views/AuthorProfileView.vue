<script setup lang="ts">
import type { AuthorResource } from '../Author'
import type { PreprintResourceList } from '@/models/Preprint/Preprint'
import type { PublicationResourceList } from '@/models/Publication/Publication'
import { watchThrottled } from '@vueuse/core'
import { computed, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import ContentCard from '@/components/ContentCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import PreprintList from '@/models/Preprint/components/PreprintList.vue'
import { PreprintQuery } from '@/models/Preprint/Preprint'
import PublicationList from '@/models/Publication/components/PublicationList.vue'
import { PublicationQuery } from '@/models/Publication/Publication'
import { AuthorService } from '../Author'
import AuthorExpertiseDisplay from '../components/AuthorExpertiseDisplay.vue'
import AuthorProfileCard from '../components/AuthorProfileCard.vue'

const props = defineProps<{
  id: number
}>()

// State variables
const author = ref<AuthorResource | null>(null)
const publications = ref<PublicationResourceList>()
const preprints = ref<PreprintResourceList>()
const loading = ref({ author: false, publications: false, preprints: false })
const currentPage = ref(1)
const preprintCurrentPage = ref(1)
const search = ref<string | null>(null)
const preprintSearch = ref<string | null>(null)

// i18n
const { t } = useI18n()

// Computed properties
const pageTitle = computed(() => {
  if (!author.value)
    return t('author-profile.title')
  const authorName = `${author.value.data.first_name} ${author.value.data.last_name}`
  return t('author-profile.author-information-for', { name: authorName })
})

const publicationCount = computed(() => {
  return publications.value?.meta?.total ?? 0
})

const hasPublications = computed(() => {
  return publications.value?.data && publications.value.data.length > 0
})

const preprintCount = computed(() => {
  return preprints.value?.meta?.total ?? 0
})

const hasPreprints = computed(() => {
  return preprints.value?.data && preprints.value.data.length > 0
})

// Methods
async function loadAuthor() {
  if (loading.value.author)
    return

  loading.value.author = true
  try {
    author.value = await AuthorService.find(props.id)
  }
  catch (error) {
    console.error('Failed to load author:', error)
    // Handle error - could navigate to 404 or show error message
  }
  finally {
    loading.value.author = false
  }
}

async function loadPublications() {
  if (loading.value.publications)
    return

  // Build the query
  let query = new PublicationQuery()

  // Add search filter if present
  if (search?.value) {
    query = query.filterTitle(search.value)
  }

  // Sort by publication date
  query.sort('published_on', 'desc')
  query.paginate(currentPage.value, 10)

  loading.value.publications = true
  try {
    publications.value = await AuthorService.getPublications(props.id, query)
  }
  catch (error) {
    console.error('Failed to load publications:', error)
  }
  finally {
    loading.value.publications = false
  }
}

async function loadPreprints() {
  if (loading.value.preprints)
    return

  // Build the query
  let query = new PreprintQuery()

  // Add search filter if present
  if (preprintSearch?.value) {
    query = query.filterTitle(preprintSearch.value)
  }

  query.paginate(preprintCurrentPage.value, 10)

  loading.value.preprints = true
  try {
    preprints.value = await AuthorService.getPreprints(props.id, query)
  }
  catch (error) {
    console.error('Failed to load preprints:', error)
  }
  finally {
    loading.value.preprints = false
  }
}

// Watchers
watch(currentPage, () => {
  loadPublications()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    loadPublications()
  },
  { throttle: 750 },
)

watch(preprintCurrentPage, () => {
  loadPreprints()
})

watchThrottled(
  preprintSearch,
  () => {
    preprintCurrentPage.value = 1
    loadPreprints()
  },
  { throttle: 750 },
)

// Event handlers
function handleAuthorUpdated() {
  loadAuthor()
}

// Lifecycle hooks
onMounted(async () => {
  await loadAuthor()
  await loadPublications()
  await loadPreprints()
})
</script>

<template>
  <MainPageLayout :title="pageTitle" class="q-pr-lg">
    <div class="row q-gutter-lg q-col-gutter-lg q-mr-lg">
      <!-- Left Column: Author Information -->
      <div class="col-lg-3 col-md-12">
        <!-- Author Profile Card -->
        <template v-if="author">
          <div class="row q-gutter-lg flex justify-between">
            <AuthorProfileCard :author="author" class="col-lg-12 col-md-6" @updated="handleAuthorUpdated" />
            <AuthorExpertiseDisplay :author="author" class="col-lg-12 col-md-5" />
          </div>
        </template>

        <!-- Loading skeleton for author info -->
        <template v-else-if="loading.author">
          <ContentCard>
            <q-skeleton height="200px" />
          </ContentCard>
        </template>

        <!-- Author not found -->
        <template v-else>
          <ContentCard>
            <div class="text-center">
              <q-icon name="mdi-account-off-outline" size="xl" color="grey-5" />
              <div class="text-h6 q-mt-md">
                {{ t('author-profile.author-not-found') }}
              </div>
            </div>
          </ContentCard>
        </template>
      </div>

      <!-- Right Column: Publications & Preprints -->
      <div class="col-lg-grow col-md-12">
        <!-- Publications -->
        <ContentCard secondary class="q-mb-lg">
          <template #title>
            {{ t('author-profile.publications') }}
          </template>
          <template #subtitle>
            <template v-if="publicationCount > 0">
              {{
                t('author-profile.publications-count', {
                  count: publicationCount,
                })
              }}
            </template>
            <template v-else>
              {{ t('author-profile.no-publications-subtitle') }}
            </template>
          </template>
          <template #title-right>
            <SearchInput
              v-model="search"
              :label="t('common.filter')"
            />
          </template>

          <!-- Publications Loading State -->
          <template v-if="loading.publications">
            <div class="q-pa-md">
              <q-skeleton height="60px" class="q-mb-md" />
              <q-skeleton height="60px" class="q-mb-md" />
              <q-skeleton height="60px" />
            </div>
          </template>

          <!-- No Publications Found -->
          <template v-else-if="!hasPublications">
            <NoResultFoundDiv />
          </template>

          <!-- Publications List -->
          <template v-else>
            <PublicationList :publications="publications?.data ?? []" />
            <q-card-section>
              <q-separator class="q-mb-md" />
              <PaginationDiv
                v-model="currentPage"
                :meta="publications?.meta ?? null"
                :disable="loading.publications"
              />
            </q-card-section>
          </template>
        </ContentCard>

        <!-- Preprints -->
        <ContentCard secondary>
          <template #title>
            {{ t('common.preprints') }}
          </template>
          <template #subtitle>
            <template v-if="preprintCount > 0">
              {{
                t('author-profile.preprints-count', {
                  count: preprintCount,
                })
              }}
            </template>
            <template v-else>
              {{ t('author-profile.no-preprints-subtitle') }}
            </template>
          </template>
          <template #title-right>
            <SearchInput
              v-model="preprintSearch"
              :label="t('common.filter')"
            />
          </template>

          <!-- Preprints Loading State -->
          <template v-if="loading.preprints">
            <div class="q-pa-md">
              <q-skeleton height="60px" class="q-mb-md" />
              <q-skeleton height="60px" class="q-mb-md" />
              <q-skeleton height="60px" />
            </div>
          </template>

          <!-- No Preprints Found -->
          <template v-else-if="!hasPreprints">
            <NoResultFoundDiv />
          </template>

          <!-- Preprints List -->
          <template v-else>
            <PreprintList :preprints="preprints?.data ?? []" />
            <q-card-section>
              <q-separator class="q-mb-md" />
              <PaginationDiv
                v-model="preprintCurrentPage"
                :meta="preprints?.meta ?? null"
                :disable="loading.preprints"
              />
            </q-card-section>
          </template>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
