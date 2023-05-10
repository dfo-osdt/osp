<template>
    <MainPageLayout :title="$t('common.my-manuscripts')">
        <template
            v-if="authorStore.user?.can('create_manuscript_records')"
            #toolbar
        >
            <div class="flex justify-end">
                <q-fab
                    v-model="showCreateManuscriptDialog"
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
                                $t('my-manuscript-records.create-manuscript')
                            }}</q-tooltip
                        >
                    </template>
                </q-fab>
            </div>
        </template>
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary no-padding>
                    <template #title>{{ $t('common.manuscripts') }}</template>
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
                        v-if="
                            authorStore.user?.can('create_manuscript_records')
                        "
                        class="flex-center flex q-mt-lg q-mb-sm q-mx-md"
                    >
                        <q-btn
                            :label="
                                $t('my-manuscript-records.create-manuscript')
                            "
                            color="primary"
                            outline
                            icon="mdi-plus"
                            @click="showCreateManuscriptDialog = true"
                        />
                    </div>
                </ContentCard>
            </div>
            <div class="col q-pr-lg">
                <ContentCard secondary>
                    <template #title>{{ mainFilter?.label }}</template>
                    <template #subtitle>{{ mainFilter?.caption }}</template>
                    <template #title-right
                        ><SearchInput
                            v-model="search"
                            :label="$t('common.filter')"
                    /></template>
                    <template
                        v-if="
                            manuscripts?.data.length === 0 && !hasAnyManuscripts
                        "
                    >
                        <NoManuscriptExistsDiv
                            :title="$t('my-manuscript-records.no-manuscript')"
                        />
                    </template>
                    <template
                        v-if="
                            manuscripts?.data.length === 0 && hasAnyManuscripts
                        "
                    >
                        <NoResultFoundDiv />
                    </template>
                    <ManuscriptList :manuscripts="manuscripts?.data ?? []" />
                    <q-card-section>
                        <PaginationDiv
                            v-model="currentPage"
                            :meta="manuscripts?.meta ?? null"
                            :disable="loading"
                        ></PaginationDiv>
                    </q-card-section>
                </ContentCard>
            </div>
        </div>
        <CreateManuscriptDialog v-model="showCreateManuscriptDialog" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import SearchInput from '@/components/SearchInput.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import CreateManuscriptDialog from '../components/CreateManuscriptDialog.vue';
import ManuscriptList from '../components/ManuscriptList.vue';
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue';
import NoManuscriptExistsDiv from '../components/NoManuscriptExistsDiv.vue';

import {
    ManuscriptQuery,
    ManuscriptRecordService,
    ManuscriptRecordSummaryResourceList,
} from '../ManuscriptRecord';
import PaginationDiv from '@/components/PaginationDiv.vue';

const { t } = useI18n();

const manuscripts = ref<ManuscriptRecordSummaryResourceList>();

onMounted(() => {
    getManuscripts();
});

const loading = ref(false);
async function getManuscripts() {
    if (loading.value) return;
    // build the query
    let query = new ManuscriptQuery();

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

    query.sort('title', 'asc');
    query.paginate(currentPage.value, 5);
    query.includeManagementReview();

    loading.value = true;
    manuscripts.value = await ManuscriptRecordService.getMyManuscripts(query);
    loading.value = false;
}

// create manuscript dialog
const showCreateManuscriptDialog = ref(false);

// user store
const authorStore = useAuthStore();

// manuscript store - used to determine if the user has any manuscripts
const manuscriptStore = useManuscriptStore();
manuscriptStore.getMyManuscripts();
const hasAnyManuscripts = computed(() => {
    if (!manuscriptStore.manuscripts) return false;
    return manuscriptStore.manuscripts?.length > 0;
});

// type for main filter options
type MainFilterOption = {
    id: number;
    label: string;
    caption?: string;
    icon: string;
    active: boolean;
    filter: (query: ManuscriptQuery) => ManuscriptQuery;
};

// content filter - sidebar
const activeFilterId = ref(1);
const mainFilterOptions = computed<MainFilterOption[]>(() => {
    return [
        {
            id: 1,
            label: t('my-manuscript-records.all-manuscripts'),
            caption: t('my-manuscript-records.all-manuscripts-caption'),
            icon: 'mdi-all-inclusive',
            active: activeFilterId.value === 1,
            filter: (query: ManuscriptQuery): ManuscriptQuery => {
                return query;
            },
        },
        {
            id: 2,
            label: t('common.my-manuscripts'),
            caption: t('my-manuscript-records.my-manuscripts-caption'),
            icon: 'mdi-account-arrow-left-outline',
            active: activeFilterId.value === 2,
            filter: (query: ManuscriptQuery): ManuscriptQuery => {
                return authorStore.user
                    ? query.filterUserId([authorStore.user.id])
                    : query;
            },
        },
        {
            id: 3,
            label: t('common.in-progress'),
            caption: t('my-manuscript-records.actions-still-required'),
            icon: 'mdi-progress-clock',
            active: activeFilterId.value === 3,
            filter: (query: ManuscriptQuery): ManuscriptQuery => {
                return query.filterStatus([
                    'draft',
                    'in_review',
                    'reviewed',
                    'submitted',
                ]);
            },
        },
        {
            id: 4,
            label: t('common.completed'),
            caption: t('common.no-actions-required'),
            icon: 'mdi-check-circle',
            active: activeFilterId.value === 4,
            filter: (query: ManuscriptQuery): ManuscriptQuery => {
                return query.filterStatus([
                    'accepted',
                    'withdrawn',
                    'withheld',
                ]);
            },
        },
    ];
});

const mainFilterClick = (filterId: number) => {
    activeFilterId.value = filterId;
    search.value = '';
    currentPage.value = 1;
    getManuscripts();
};

const mainFilter = computed(() => {
    return mainFilterOptions.value.find((f) => f.active);
});

// pagination
const currentPage = ref(1);
watch(currentPage, () => {
    getManuscripts();
});

// search
const search = ref<string | null>(null);

watchThrottled(
    search,
    () => {
        currentPage.value = 1;
        getManuscripts();
    },
    { throttle: 750 }
);
</script>

<style scoped></style>
