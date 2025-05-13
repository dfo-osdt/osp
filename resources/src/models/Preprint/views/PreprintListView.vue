<script setup lang="ts">
import type { PreprintResourceList } from '../Preprint'
import ContentCard from '@/components/ContentCard.vue'
import NoResultsFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import SearchInput from '@/components/SearchInput.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import PreprintList from '../components/PreprintList.vue'
import { PreprintQuery, PreprintService } from '../Preprint'

const { t } = useI18n()

const preprints = ref<PreprintResourceList>()
const loading = ref(false)
const currentPage = ref(1)

// filter
const showFilters = ref(false)
const search = ref('')
const authorId = ref<number | null>(null)
const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null)

const filterCaption = computed(() => {
  let caption = ''
  if (authorId.value) {
    const { first_name, last_name } = authorSelect?.value?.selectedAuthor?.data || {}
    caption += `${t('common.by')} ${first_name || 'NA'} ${last_name || 'NA'} `
  }
  if (caption.length > 0)
    caption = `${t('common.preprint')} ${caption.slice(0, -1)}`
  else caption = t('common.no-filters-applied')
  return caption
})

onMounted(() => {
  getPreprints()
})

async function getPreprints() {
  if (loading.value)
    return

  let query = new PreprintQuery()

  if (search?.value) {
    query = query.filterTitle(search.value)
  }

  if (authorId.value) {
    query = query.filterAuthorID([authorId.value])
  }

  query.paginate(currentPage.value, 10)

  loading.value = true
  preprints.value = await PreprintService.list(query)
  loading.value = false
}

// Watchers
watch(currentPage, () => {
  getPreprints()
})

watchThrottled(
  search,
  () => {
    currentPage.value = 1
    getPreprints()
  },
  { throttle: 750 },
)

watch(authorId, () => {
  currentPage.value = 1
  getPreprints()
})
</script>

<template>
  <MainPageLayout title="Preprints">
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="col q-pr-lg">
        <ContentCard secondary>
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
          <template v-if="preprints?.data.length === 0">
            <NoResultsFoundDiv />
          </template>
          <PreprintList :preprints="preprints?.data ?? []" />
          <q-card-section>
            <q-separator class="q-mb-md" />
            <PaginationDiv
              v-model="currentPage"
              :meta="preprints?.meta ?? null"
              :disable="loading"
            />
          </q-card-section>
        </ContentCard>
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped>

</style>
