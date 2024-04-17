<template>
    <main-page-layout icon="mdi-view-dashboard" :title="$t('common.dashboard')">
        <div class="q-pa-md">
            <div class="row">
                <div
                    v-for="card in data"
                    :key="card.title"
                    class="col-12 col-sm-4 q-pa-sm"
                >
                    <metric-card
                        :title="card.title"
                        :value="card.value"
                        :subtitle="card.subtitle"
                        :to="card.to"
                    />
                </div>
            </div>
            <div class="q-pa-sm">
                <content-card>
                    <template #title>
                        <q-icon
                            name="mdi-history"
                            color="primary"
                            size="sm"
                            left
                        />
                        <span>{{ $t('common.recent') }}</span>
                    </template>
                    <template #nav>
                        <q-tabs
                            v-model="tab"
                            dense
                            narrow-indicator
                            align="left"
                            indicator-color="primary"
                            active-color="primary"
                            class="text-grey-8"
                        >
                            <q-tab
                                name="manuscripts"
                                :label="$t('common.manuscripts')"
                            />
                            <q-tab name="reviews">
                                <span class="text-weight-medium q-pr-xs">{{
                                    $t('common.reviews')
                                }}</span>
                                <q-badge
                                    v-if="reviewSteps.pendingReviewCount > 0"
                                    color="primary"
                                    rounded
                                    floating
                                    transparent
                                    >{{
                                        reviewSteps.pendingReviewCount
                                    }}</q-badge
                                >
                            </q-tab>
                            <q-tab
                                name="publications"
                                :label="$t('common.publications')"
                            />
                        </q-tabs>
                        <q-separator />
                    </template>

                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="manuscripts" class="q-pa-none">
                            <template
                                v-if="manuscripts.empty && !manuscripts.loading"
                            >
                                <NoManuscriptExistsDiv
                                    :title="
                                        $t(
                                            'dashboard.create-your-first-manuscript-record',
                                        )
                                    "
                                />
                            </template>
                            <ManuscriptList
                                v-if="!manuscripts.empty"
                                class="q-mb-lg"
                                :manuscripts="manuscripts.recentManuscripts"
                                @deleted="manuscripts.getMyManuscripts(true)"
                            />
                            <div class="text-center">
                                <q-btn
                                    rounded
                                    color="primary"
                                    class="q-mb-md"
                                    @click="showCreateManuscript = true"
                                >
                                    {{
                                        $t(
                                            'my-manuscript-records.create-manuscript',
                                        )
                                    }}
                                </q-btn>
                                <create-manuscript-dialog
                                    v-if="showCreateManuscript"
                                    v-model="showCreateManuscript"
                                />
                            </div>
                        </q-tab-panel>
                        <q-tab-panel name="reviews" class="q-pa-none">
                            <RecentManagementReviewStepsView />
                        </q-tab-panel>
                        <q-tab-panel name="publications" class="q-pa-none">
                            <template v-if="publications.empty">
                                <NoPublicationExistDiv
                                    :title="
                                        $t(
                                            'dashboard.add-your-first-publication',
                                        )
                                    "
                                />
                            </template>
                            <PublicationList
                                v-if="!publications.empty"
                                class="q-mb-lg"
                                :publications="publications.recentPublications"
                            />
                            <div class="text-center q-mb-md">
                                <q-btn
                                    rounded
                                    color="primary"
                                    @click="showCreatePublication = true"
                                >
                                    {{ $t('dashboard.add-publication') }}
                                </q-btn>
                            </div>
                            <CreatePublicationDialog
                                v-if="showCreatePublication"
                                v-model="showCreatePublication"
                                @created="publicationCreated"
                            />
                        </q-tab-panel>
                    </q-tab-panels>
                </content-card>
            </div>
        </div>
    </main-page-layout>
</template>

<script setup lang="ts">
import MetricCard from '@/components/MetricCard.vue';
import ContentCard from '../components/ContentCard.vue';
import CreateManuscriptDialog from '@/models/ManuscriptRecord/components/CreateManuscriptDialog.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManuscriptList from '@/models/ManuscriptRecord/components/ManuscriptList.vue';
import RecentManagementReviewStepsView from '@/models/ManagementReviewStep/views/RecentManagementReviewStepsView.vue';
import PublicationList from '@/models/Publication/components/PublicationList.vue';
import CreatePublicationDialog from '@/models/Publication/components/CreatePublicationDialog.vue';
import { PublicationResource } from '@/models/Publication/Publication';
import NoManuscriptExistsDiv from '@/models/ManuscriptRecord/components/NoManuscriptExistsDiv.vue';
import NoPublicationExistDiv from '@/models/Publication/components/NoPublicationExistDiv.vue';

const { t } = useI18n();
const manuscripts = useManuscriptStore();
const reviewSteps = useManagementReviewStepStore();
const publications = usePublicationStore();

const tab = useStorage('dashboard-recent-tab', 'manuscripts');

// load the latest manuscripts
onMounted(() => {
    manuscripts.getMyManuscripts(true);
    reviewSteps.getMyManagementReviewSteps(true);
    publications.getMyPublications(true);
});

const data = computed(() => [
    {
        title: t('common.my-manuscripts'),
        value: manuscripts.inProgressManuscripts.length,
        subtitle: t('dashboard.in-progress'),
        to: '/my-manuscripts',
    },
    {
        title: t('common.my-reviews'),
        value: reviewSteps.pendingReviewCount,
        subtitle: t('common.pending'),
        to: '/my-reviews',
    },
    {
        title: t('common.my-publications'),
        value: publications.overduePublications,
        subtitle: t('dashboard.awaiting-publication'),
        to: '/my-publications',
    },
]);

// manuscript section
const showCreateManuscript = ref(false);

// publication section
const showCreatePublication = ref(false);

/**
 * Closes the dialog and adds the created publication
 * to the publication store.
 *
 * @param publication the publication that was created
 */
const publicationCreated = (publication: PublicationResource) => {
    if (publications.publications) publications.publications.push(publication);
    showCreatePublication.value = false;
};
</script>
