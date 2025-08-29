<script setup lang="ts">
import type { ManuscriptRecordResourceList } from '../ManuscriptRecord';
import { watchThrottled } from '@vueuse/core';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import ContentCard from '@/components/ContentCard.vue';
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue';
import PaginationDiv from '@/components/PaginationDiv.vue';
import SearchInput from '@/components/SearchInput.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue';
import FunctionalAreaSelect from '@/models/FunctionalArea/components/FunctionalAreaSelect.vue';
import RegionSelect from '@/models/Region/components/RegionSelect.vue';
import ManuscriptList from '../components/ManuscriptList.vue';
import {
  ManuscriptRecordListQuery,
  ManuscriptRecordService,
} from '../ManuscriptRecord';

// Permission check - only show to users with regional view permissions
const authStore = useAuthStore();
const router = useRouter();

// Check if user has permission (but don't redirect automatically)
const hasPermission = computed(() => {
  // Wait for auth to load before making permission decisions
  if (authStore.loading || !authStore.user) return null;
  return authStore.canViewRegionalManuscripts;
});

// State variables
const manuscripts = ref<ManuscriptRecordResourceList>();
const loading = ref(false);
const activeFilter = ref(1);
const currentPage = ref(1);
const search = ref<string | null>(null);
const showFilters = ref(false);
const regionId = ref<number | null>(null);
const regionSelect = ref<InstanceType<typeof RegionSelect> | null>(null);
const functionalAreaId = ref<number | null>(null);
const functionalAreaSelect = ref<InstanceType<
  typeof FunctionalAreaSelect
> | null>(null);
const authorId = ref<number | null>(null);
const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null);

// i18n
const { t } = useI18n();

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
        return query;
      },
    },
    {
      id: 2,
      label: t('regional-manuscripts.draft-in-review'),
      caption: t('regional-manuscripts.manuscripts-needing-attention'),
      icon: 'mdi-progress-clock',
      active: activeFilter.value === 2,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterStatus(['draft', 'in_review', 'reviewed']);
      },
    },
    {
      id: 3,
      label: t('regional-manuscripts.under-management-review'),
      caption: t('regional-manuscripts.in-management-review-process'),
      icon: 'mdi-account-check-outline',
      active: activeFilter.value === 3,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterStatus(['submitted']);
      },
    },
    {
      id: 4,
      label: t('common.completed'),
      caption: t('regional-manuscripts.accepted-published-manuscripts'),
      icon: 'mdi-check-circle',
      active: activeFilter.value === 4,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterStatus(['accepted', 'published']);
      },
    },
    {
      id: 5,
      label: t('regional-manuscripts.potential-public-interest'),
      caption: t('regional-manuscripts.manuscripts-with-public-interest'),
      icon: 'mdi-eye-outline',
      active: activeFilter.value === 5,
      filter: (query: ManuscriptRecordListQuery): ManuscriptRecordListQuery => {
        return query.filterPotentialPublicInterest(true);
      },
    },
  ];
});

// Computed properties
const mainFilter = computed(() => {
  return mainFilterOptions.value.find((f) => f.active);
});

const filterCaption = computed(() => {
  let caption = '';
  if (regionId.value) {
    const regionData = regionSelect?.value?.selectedRegion?.data;
    if (regionData) {
      const regionName = regionData.name_en || regionData.name_fr || 'NA';
      caption += `${t('common.in')} ${regionName} `;
    }
  }
  if (functionalAreaId.value) {
    const faData = functionalAreaSelect?.value?.selectedFunctionalArea?.data;
    if (faData) {
      const faName = faData.name_en || faData.name_fr || 'NA';
      caption += `${t('common.for')} ${faName} `;
    }
  }
  if (authorId.value) {
    const { first_name, last_name } =
      authorSelect?.value?.selectedAuthor?.data || {};
    caption += `${t('common.by')} ${first_name || 'NA'} ${last_name || 'NA'} `;
  }
  if (caption.length > 0)
    caption = `${t('common.manuscripts')} ${caption.slice(0, -1)}`;
  else caption = t('common.no-filters-applied');
  return caption;
});

// Methods
async function getManuscripts() {
  if (loading.value) return;
  // build the query
  let query = new ManuscriptRecordListQuery();

  // apply the active main filters
  mainFilterOptions.value.forEach((f) => {
    if (f.active) {
      query = f.filter(query);
    }
  });

  // is there a search term?
  if (search?.value) {
    query = query.filterTitle([search.value]);
  }

  if (regionId.value) {
    query = query.filterRegionId([regionId.value]);
  }

  if (functionalAreaId.value) {
    query = query.filterFunctionalAreaId([functionalAreaId.value]);
  }

  if (authorId.value) {
    query = query.filterAuthorId([authorId.value]);
  }

  query.sort('updated_at', 'desc');
  query.paginate(currentPage.value, 10);

  loading.value = true;
  manuscripts.value = await ManuscriptRecordService.list(query);
  loading.value = false;
}

function mainFilterClick(filterId: number) {
  activeFilter.value = filterId;
  search.value = '';
  currentPage.value = 1;
  getManuscripts();
}

// Watchers
watch(currentPage, () => {
  getManuscripts();
});

watchThrottled(
  search,
  () => {
    currentPage.value = 1;
    getManuscripts();
  },
  { throttle: 750 },
);

watch(regionId, () => {
  currentPage.value = 1;
  getManuscripts();
});

watch(functionalAreaId, () => {
  currentPage.value = 1;
  getManuscripts();
});

watch(authorId, () => {
  currentPage.value = 1;
  getManuscripts();
});

// Lifecycle hooks - only load data if user has permission
onMounted(() => {
  if (hasPermission.value) {
    getManuscripts();
  }
});

// Type definitions
interface MainFilterOption {
  id: number;
  label: string;
  caption?: string;
  icon: string;
  active: boolean;
  filter: (query: ManuscriptRecordListQuery) => ManuscriptRecordListQuery;
}
</script>

<template>
  <MainPageLayout :title="$t('common.regional-manuscripts')">
    <!-- Show loading state while auth is loading -->
    <div v-if="authStore.loading" class="flex justify-center q-pa-xl">
      <q-spinner-dots size="lg" />
    </div>

    <!-- Show permission denied message if user doesn't have access -->
    <div
      v-else-if="hasPermission === false"
      class="flex justify-center q-pa-xl"
    >
      <ContentCard>
        <template #title>
          {{ $t('common.access-denied') }}
        </template>
        <div class="text-center">
          <q-icon name="mdi-lock" size="4rem" color="grey-5" class="q-mb-md" />
          <p class="text-body1 q-mb-md">
            {{ $t('regional-manuscripts.permission-required') }}
          </p>
          <q-btn
            :label="$t('common.go-to-dashboard')"
            color="primary"
            @click="router.push('/dashboard')"
          />
        </div>
      </ContentCard>
    </div>

    <!-- Show main content if user has permission -->
    <div
      v-else-if="hasPermission === true"
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
                  :disable="loading"
                />
                <FunctionalAreaSelect
                  ref="functionalAreaSelect"
                  v-model="functionalAreaId"
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
