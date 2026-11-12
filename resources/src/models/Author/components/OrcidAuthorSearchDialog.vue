<script setup lang="ts">
import type {
  OrcidAuthorImport,
  OrcidAuthorPreview,
  OrcidAuthorSearch,
  OrcidAuthorSearchResult,
} from '../Author'
import BaseDialog from '@/components/BaseDialog.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import { AuthorService } from '../Author'

const props = defineProps<{
  initialQuery?: string
}>()

const emit = defineEmits<{
  (event: 'import', payload: OrcidAuthorImport): void
  (event: 'useExisting', authorId: number): void
}>()

const { t } = useI18n()

// searches wait for a pause in typing, and for enough of a name to be useful
const MIN_QUERY_LENGTH = 3

const query = ref(props.initialQuery ?? '')
const search = ref<OrcidAuthorSearch | null>(null)
const searching = ref(false)

const preview = ref<OrcidAuthorPreview | null>(null)
const previewing = ref<string | null>(null)
const selectedAffiliation = ref<number | null>(null)

// ORCID can answer slower than people type, so only the latest search may show its results
let latestSearch = 0

async function runSearch() {
  const text = query.value.trim()
  if (text.length < MIN_QUERY_LENGTH) {
    return
  }
  const thisSearch = ++latestSearch
  searching.value = true
  try {
    const found = await AuthorService.searchOrcid(text)
    if (thisSearch === latestSearch) {
      search.value = found
    }
  }
  catch {
    // the http client already tells the user what went wrong
  }
  finally {
    if (thisSearch === latestSearch) {
      searching.value = false
    }
  }
}

watch(query, () => runSearch())

onMounted(() => runSearch())

async function showPreview(result: OrcidAuthorSearchResult) {
  previewing.value = result.orcid
  try {
    preview.value = await AuthorService.previewOrcid(result.orcid.split('/').pop() ?? '')
    const affiliations = preview.value.author?.affiliations ?? []
    const suggested = affiliations.findIndex(affiliation => affiliation.suggested)
    selectedAffiliation.value = suggested === -1
      ? null
      : suggested
  }
  catch {
    // the http client already tells the user what went wrong
  }
  finally {
    previewing.value = null
  }
}

const previewedAuthor = computed(() => preview.value?.author ?? null)

function importAuthor() {
  const author = previewedAuthor.value
  if (!author) {
    return
  }
  const affiliation = selectedAffiliation.value === null
    ? null
    : author.affiliations[selectedAffiliation.value] ?? null
  emit('import', {
    orcid: author.orcid,
    first_name: author.first_name,
    last_name: author.last_name,
    email: author.emails[0] ?? null,
    organization_id: affiliation?.organization_id ?? null,
    affiliation_name: affiliation?.name ?? null,
  })
}

const affiliationOptions = computed(() => (previewedAuthor.value?.affiliations ?? []).map((affiliation, index) => ({
  value: index,
  label: affiliation.name ?? t('orcid-author-search-dialog.unnamed-organization'),
  affiliation,
})))
</script>

<template>
  <BaseDialog :title="$t('orcid-author-search-dialog.title')">
    <!-- Preview: what the record fills in -->
    <template v-if="preview">
      <q-card-section v-if="preview.status === 'unavailable' || !previewedAuthor">
        <q-banner class="bg-orange-1 text-orange-10" rounded>
          {{ $t('orcid-author-search-dialog.unavailable') }}
        </q-banner>
      </q-card-section>
      <q-card-section v-else>
        <q-banner
          v-if="previewedAuthor.existing_author_id"
          class="bg-blue-1 text-blue-10 q-mb-md"
          rounded
        >
          {{ $t('orcid-author-search-dialog.already-in-osp') }}
          <template #action>
            <q-btn
              flat
              color="primary"
              :label="$t('orcid-author-search-dialog.use-existing-author')"
              @click="emit('useExisting', previewedAuthor.existing_author_id)"
            />
          </template>
        </q-banner>

        <div class="text-subtitle2 text-grey-8 q-mb-sm">
          {{ $t('orcid-author-search-dialog.will-be-filled') }}
        </div>
        <q-list bordered separator class="rounded-borders q-mb-md">
          <q-item>
            <q-item-section>
              <q-item-label caption>
                {{ $t('orcid-author-search-dialog.orcid-id') }}
              </q-item-label>
              <q-item-label>
                <a
                  :href="previewedAuthor.orcid"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {{ previewedAuthor.orcid.split('/').pop() }}
                  <q-icon name="mdi-open-in-new" />
                </a>
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label caption>
                {{ $t('common.first-name') }}
              </q-item-label>
              <q-item-label :class="{ 'text-orange-9': !previewedAuthor.first_name }">
                {{ previewedAuthor.first_name ?? $t('orcid-author-search-dialog.not-public') }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label caption>
                {{ $t('common.last-name') }}
              </q-item-label>
              <q-item-label :class="{ 'text-orange-9': !previewedAuthor.last_name }">
                {{ previewedAuthor.last_name ?? $t('orcid-author-search-dialog.not-public') }}
              </q-item-label>
              <q-item-label v-if="previewedAuthor.credit_name" caption>
                {{ $t('orcid-author-search-dialog.published-name', { name: previewedAuthor.credit_name }) }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label caption>
                {{ $t('common.email') }}
              </q-item-label>
              <q-item-label :class="{ 'text-orange-9': previewedAuthor.emails.length === 0 }">
                {{ previewedAuthor.emails[0] ?? $t('orcid-author-search-dialog.no-public-email') }}
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label caption>
                {{ $t('common.affiliation') }}
              </q-item-label>
              <q-item-label v-if="affiliationOptions.length === 0" class="text-orange-9">
                {{ $t('orcid-author-search-dialog.no-current-employment') }}
              </q-item-label>
              <q-option-group
                v-else
                v-model="selectedAffiliation"
                :options="affiliationOptions"
                dense
              >
                <template #label="option">
                  <div>
                    {{ option.label }}
                    <div class="text-caption text-grey-7">
                      {{ option.affiliation.organization_id
                        ? $t('orcid-author-search-dialog.organization-found')
                        : $t('orcid-author-search-dialog.organization-to-pick') }}
                    </div>
                  </div>
                </template>
              </q-option-group>
            </q-item-section>
          </q-item>
        </q-list>
        <div class="text-caption text-grey-7">
          {{ $t('orcid-author-search-dialog.review-before-saving') }}
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat :label="$t('common.back')" @click="preview = null" />
        <q-btn
          color="primary"
          :label="$t('orcid-author-search-dialog.import')"
          :disable="!previewedAuthor"
          @click="importAuthor"
        />
      </q-card-actions>
    </template>

    <!-- Search -->
    <template v-else>
      <q-card-section>
        <q-input
          v-model="query"
          outlined
          autofocus
          debounce="500"
          :label="$t('orcid-author-search-dialog.search-label')"
          :hint="$t('orcid-author-search-dialog.search-hint')"
          :loading="searching"
        >
          <template #prepend>
            <OrcidIcon />
          </template>
        </q-input>
      </q-card-section>

      <q-card-section v-if="!search" class="q-pt-none text-body2 text-grey-8">
        {{ $t('orcid-author-search-dialog.intro') }}
      </q-card-section>

      <q-card-section v-else class="q-pt-none">
        <q-banner
          v-if="search.status === 'unavailable'"
          class="bg-orange-1 text-orange-10 q-mb-md"
          rounded
        >
          {{ $t('orcid-author-search-dialog.unavailable') }}
        </q-banner>
        <div
          v-else-if="search.results.length === 0"
          class="text-body2 text-grey-8 q-mb-md"
        >
          {{ $t('orcid-author-search-dialog.no-results') }}
        </div>

        <q-list v-if="search.results.length > 0" bordered separator class="rounded-borders">
          <q-item v-for="result in search.results" :key="result.orcid">
            <q-item-section>
              <q-item-label class="text-weight-medium">
                {{ result.name ?? result.orcid }}
              </q-item-label>
              <q-item-label v-if="result.other_names.length > 0" caption>
                {{ $t('orcid-author-search-dialog.also-known-as', { names: result.other_names.join(', ') }) }}
              </q-item-label>
              <q-item-label v-if="result.institutions.length > 0" caption>
                {{ result.institutions.join(' · ') }}
              </q-item-label>
              <q-item-label v-if="result.emails.length > 0" caption>
                {{ result.emails.join(', ') }}
              </q-item-label>
              <q-item-label caption>
                <a
                  :href="result.orcid"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {{ result.orcid }}
                  <q-icon name="mdi-open-in-new" />
                </a>
              </q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-btn
                v-if="result.existing_author_id"
                outline
                color="primary"
                :label="$t('orcid-author-search-dialog.use-existing-author')"
                @click="emit('useExisting', result.existing_author_id)"
              >
                <q-tooltip class="text-body2">
                  {{ $t('orcid-author-search-dialog.already-in-osp') }}
                </q-tooltip>
              </q-btn>
              <q-btn
                v-else
                color="primary"
                :label="$t('orcid-author-search-dialog.preview')"
                :loading="previewing === result.orcid"
                @click="showPreview(result)"
              />
            </q-item-section>
          </q-item>
        </q-list>

        <div class="text-caption text-grey-7 q-mt-md">
          <span v-if="search.num_found > search.results.length">
            {{ $t('orcid-author-search-dialog.more-results', { shown: search.results.length, found: search.num_found }) }}
          </span>
          {{ $t('orcid-author-search-dialog.not-here') }}
          <a :href="search.orcid_search_url" target="_blank" rel="noopener noreferrer">
            {{ $t('orcid-author-search-dialog.search-on-orcid') }}
            <q-icon name="mdi-open-in-new" />
          </a>
        </div>
      </q-card-section>
    </template>
  </BaseDialog>
</template>

<style scoped></style>
