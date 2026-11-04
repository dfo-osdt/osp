<script setup lang="ts">
import type { QTableProps } from 'quasar'
import type { AuthorOrcidRecord, AuthorOrcidWork, OrcidWorkType } from '../Author'
import ContentCard from '@/components/ContentCard.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import SearchInput from '@/components/SearchInput.vue'

const props = defineProps<{
  orcidRecord: AuthorOrcidRecord | null
}>()

const { t, locale } = useI18n()
const { formatFuzzyDate } = useOrcidFuzzyDate()

const search = ref<string | null>(null)
const typeFilter = ref<OrcidWorkType | null>(null)
const pagination = ref({
  sortBy: 'date',
  descending: true,
  page: 1,
  rowsPerPage: 10,
})

// the works not already listed in the author's OSP publications
const works = computed(() => (props.orcidRecord?.record?.works ?? [])
  .map((work, index) => ({ ...work, key: index })))
const alreadyListedCount = computed(() => props.orcidRecord?.record?.works_already_listed ?? 0)

const typeLabels = computed<Record<OrcidWorkType, string>>(() => ({
  'annotation': t('orcid-work-types.annotation'),
  'artistic-performance': t('orcid-work-types.artistic-performance'),
  'blog-post': t('orcid-work-types.blog-post'),
  'book-chapter': t('orcid-work-types.book-chapter'),
  'book-review': t('orcid-work-types.book-review'),
  'book': t('orcid-work-types.book'),
  'cartographic-material': t('orcid-work-types.cartographic-material'),
  'clinical-study': t('orcid-work-types.clinical-study'),
  'conference-abstract': t('orcid-work-types.conference-abstract'),
  'conference-output': t('orcid-work-types.conference-output'),
  'conference-paper': t('orcid-work-types.conference-paper'),
  'conference-poster': t('orcid-work-types.conference-poster'),
  'conference-presentation': t('orcid-work-types.conference-presentation'),
  'conference-proceedings': t('orcid-work-types.conference-proceedings'),
  'data-management-plan': t('orcid-work-types.data-management-plan'),
  'data-set': t('orcid-work-types.data-set'),
  'design': t('orcid-work-types.design'),
  'dictionary-entry': t('orcid-work-types.dictionary-entry'),
  'disclosure': t('orcid-work-types.disclosure'),
  'dissertation-thesis': t('orcid-work-types.dissertation-thesis'),
  'edited-book': t('orcid-work-types.edited-book'),
  'encyclopedia-entry': t('orcid-work-types.encyclopedia-entry'),
  'image': t('orcid-work-types.image'),
  'invention': t('orcid-work-types.invention'),
  'journal-article': t('orcid-work-types.journal-article'),
  'journal-issue': t('orcid-work-types.journal-issue'),
  'learning-object': t('orcid-work-types.learning-object'),
  'lecture-speech': t('orcid-work-types.lecture-speech'),
  'license': t('orcid-work-types.license'),
  'magazine-article': t('orcid-work-types.magazine-article'),
  'manual': t('orcid-work-types.manual'),
  'moving-image': t('orcid-work-types.moving-image'),
  'musical-composition': t('orcid-work-types.musical-composition'),
  'newsletter-article': t('orcid-work-types.newsletter-article'),
  'newspaper-article': t('orcid-work-types.newspaper-article'),
  'online-resource': t('orcid-work-types.online-resource'),
  'other': t('orcid-work-types.other'),
  'patent': t('orcid-work-types.patent'),
  'physical-object': t('orcid-work-types.physical-object'),
  'preprint': t('orcid-work-types.preprint'),
  'registered-copyright': t('orcid-work-types.registered-copyright'),
  'report': t('orcid-work-types.report'),
  'research-technique': t('orcid-work-types.research-technique'),
  'research-tool': t('orcid-work-types.research-tool'),
  'review': t('orcid-work-types.review'),
  'software': t('orcid-work-types.software'),
  'sound': t('orcid-work-types.sound'),
  'spin-off-company': t('orcid-work-types.spin-off-company'),
  'standards-and-policy': t('orcid-work-types.standards-and-policy'),
  'supervised-student-publication': t('orcid-work-types.supervised-student-publication'),
  'technical-standard': t('orcid-work-types.technical-standard'),
  'test': t('orcid-work-types.test'),
  'trademark': t('orcid-work-types.trademark'),
  'transcription': t('orcid-work-types.transcription'),
  'translation': t('orcid-work-types.translation'),
  'website': t('orcid-work-types.website'),
  'working-paper': t('orcid-work-types.working-paper'),
}))

// a type missing from the labels (e.g. added to ORCID since) shows as "Other"
function typeLabel(type: OrcidWorkType) {
  return typeLabels.value[type] ?? typeLabels.value.other
}

// only the types of this author's works
const typeOptions = computed(() => [
  ...new Set(works.value.map(work => work.type)),
]
  .map(type => ({
    label: typeLabel(type),
    value: type,
  }))
  .sort((a, b) => a.label.localeCompare(b.label, locale.value)))

const filteredWorks = computed(() => {
  const needle = search.value?.trim().toLocaleLowerCase() ?? ''
  return works.value.filter(work => (typeFilter.value === null || work.type === typeFilter.value)
    && (needle === '' || (work.title ?? '').toLocaleLowerCase().includes(needle)))
})

type QTableColumnProps = QTableProps['columns']
const columns = computed<QTableColumnProps>(() => [
  {
    name: 'title',
    label: t('common.title'),
    field: 'title',
    align: 'left',
    sortable: true,
  },
  {
    name: 'type',
    label: t('author-profile.orcid-work-type'),
    field: 'type',
    format: (type: OrcidWorkType) => typeLabel(type),
    align: 'left',
    sortable: true,
  },
  {
    name: 'date',
    label: t('author-profile.orcid-work-date'),
    field: 'date',
    format: (date: string | null) => date
      ? formatFuzzyDate(date)
      : '-',
    align: 'left',
    sortable: true,
  },
])

// a partial date sorts as the first of its month or year
function dateSortKey(date: string | null) {
  if (!date)
    return null
  const [year, month = '01', day = '01'] = date.split('-')
  return `${year}-${month}-${day}`
}

function sortWorks(rows: readonly AuthorOrcidWork[], sortBy: string, descending: boolean) {
  const direction = descending
    ? -1
    : 1

  return [...rows].sort((a, b) => {
    if (sortBy === 'date') {
      const [aKey, bKey] = [
        dateSortKey(a.date),
        dateSortKey(b.date),
      ]
      // undated works sort last, whatever the direction
      if (aKey === bKey)
        return 0
      if (aKey === null)
        return 1
      if (bKey === null)
        return -1
      return direction * aKey.localeCompare(bKey)
    }

    const value = (work: AuthorOrcidWork) => sortBy === 'type'
      ? typeLabel(work.type)
      : work.title ?? ''
    return direction * value(a).localeCompare(value(b), locale.value)
  })
}

function workLink(work: AuthorOrcidWork) {
  return work.doi
    ? `https://doi.org/${work.doi}`
    : work.url
}

// back to the first page when the works or the filters change
watch([
  () => props.orcidRecord,
  search,
  typeFilter,
], () => {
  pagination.value.page = 1
})
watch(() => props.orcidRecord, () => {
  search.value = null
  typeFilter.value = null
})
</script>

<template>
  <ContentCard v-if="works.length > 0" secondary>
    <template #title>
      <OrcidIcon size="sm" class="q-mr-sm" />
      {{ t('author-profile.orcid-other-works') }}
    </template>
    <template #subtitle>
      {{ t('author-profile.orcid-works-count', { count: works.length }) }}
    </template>
    <template #title-right>
      <div class="row q-gutter-sm items-center">
        <q-select
          v-model="typeFilter"
          :options="typeOptions"
          :label="t('author-profile.orcid-work-type')"
          emit-value
          map-options
          clearable
          dense
          outlined
          style="min-width: 180px"
        />
        <SearchInput
          v-model="search"
          :label="t('author-profile.orcid-works-search')"
        />
      </div>
    </template>

    <q-table
      v-model:pagination="pagination"
      flat
      wrap-cells
      row-key="key"
      :columns="columns"
      :rows="filteredWorks"
      :sort-method="sortWorks"
      :rows-per-page-options="[10, 25, 50, 0]"
      :no-results-label="t('common.no-results-found')"
    >
      <template #body-cell-title="cell">
        <q-td :props="cell">
          <!-- plain text from ORCID: interpolated (escaped), never rendered as HTML -->
          <div class="orcid-text">
            <a
              v-if="workLink(cell.row)"
              :href="workLink(cell.row) ?? undefined"
              target="_blank"
              rel="noopener noreferrer"
              class="text-primary"
            >{{ cell.row.title ?? t('common.no-title') }}<q-icon name="mdi-open-in-new" class="q-ml-xs" /></a>
            <template v-else>
              {{ cell.row.title ?? t('common.no-title') }}
            </template>
          </div>
          <div v-if="cell.row.journal" class="text-caption text-grey-7 orcid-text">
            {{ cell.row.journal }}
          </div>
        </q-td>
      </template>
    </q-table>

    <div v-if="alreadyListedCount > 0" class="text-caption text-grey-7 q-mt-sm">
      {{ t('author-profile.orcid-works-already-listed', alreadyListedCount) }}
    </div>
  </ContentCard>
</template>

<style scoped>
.orcid-text {
  overflow-wrap: anywhere;
}
</style>
