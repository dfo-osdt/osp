<script setup lang="ts">
import type {
  JournalResource,
  JournalResourceList,
} from '../Journal'
import { QSelect } from 'quasar'
import { SpatieQuery } from '@/api/SpatieQuery'
import {
  JournalService,
} from '../Journal'

const props = defineProps<{
  modelValue: number | null
  dfoSeriesOnly?: boolean
  hideDfoSeries?: boolean
}>()

const emit = defineEmits(['update:modelValue'])

const { t } = useI18n()

const journalSelect = ref<QSelect | null>(null)

const journals = ref<JournalResourceList>({ data: [] })
const selectedJournal = ref<JournalResource | null>(null)
const lastSearchTerm = ref('')
const journalsLoading = ref(false)
const isReadOnly = computed(
  () => journalSelect.value?.$props.readonly ?? false,
)

watch(selectedJournal, (journal) => {
  if (journal) {
    emit('update:modelValue', journal.data.id)
  }
  else {
    emit('update:modelValue', null)
  }
})

onMounted(async () => {
  if (props.modelValue) {
    const journal = await JournalService.get(props.modelValue)
    selectedJournal.value = journal
  }

  if (props.dfoSeriesOnly) {
    const query = new SpatieQuery()
    query.filter('dfo_series', 'true')
    query.paginate(1, 30)
    journals.value = await JournalService.list(query)
  }
})

async function filterJournals(val: string, update: (arg: () => Promise<void>) => void) {
  lastSearchTerm.value = val
  update(async () => {
    if (val !== '') {
      const needle = val.toLowerCase()
      journalsLoading.value = true

      const query = new SpatieQuery()
      query
        .filter('search', needle)
        .sort('title-length', 'asc')
        .sort('title', 'asc')
        .paginate(1, 12)

      if (props.dfoSeriesOnly) {
        query.filter('dfo_series', 'true')
      }

      if (props.hideDfoSeries) {
        query.filter('not_dfo_series', 'true')
      }

      journals.value = await JournalService.list(query)
      journalsLoading.value = false
    }
  })
}

function optionValue(item: JournalResource) {
  return item.data.id
}
function optionLabel(item: JournalResource) {
  const { title, issn } = item.data
  return `${title} (ISSN:${issn})`
}

defineExpose({
  selectedJournal,
})

// locale
</script>

<template>
  <QSelect
    ref="journalSelect"
    v-model="selectedJournal"
    :options="journals.data"
    :option-value="optionValue"
    :option-label="optionLabel"
    clearable
    :label="t('common.journal')"
    :loading="journalsLoading"
    use-input
    stack-label
    outlined
    :hint="t('journal-select.search-hint')"
    :hide-hint="isReadOnly"
    @filter="filterJournals"
    @clear="selectedJournal = null"
  >
    <template #no-option>
      <q-item>
        <q-item-section class="text-grey">
          <template v-if="lastSearchTerm === ''">
            {{ t('journal-select.search-hint') }}
          </template>
          <template v-else>
            {{ t('common.no-results-found-for') }}
            <strong>{{ lastSearchTerm }}</strong>
          </template>
        </q-item-section>
      </q-item>
      <q-separator />
      <q-item>
        <q-item-section>
          {{ t('journal-select.not-found-msg') }}
        </q-item-section>
      </q-item>
    </template>
    <template #option="{ itemProps, opt }">
      <q-item v-bind="itemProps">
        <q-item-section>
          <q-item-label>{{ opt.data.title }}</q-item-label>
          <q-item-label caption>
            {{ opt.data.publisher }} - {{ opt.data.issn }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
  </QSelect>
</template>

<style scoped></style>
