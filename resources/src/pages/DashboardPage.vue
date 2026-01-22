<script setup lang="ts">
import { useRouteQuery } from '@vueuse/router'
import GuideCard from '@/components/GuideCard.vue'
import MetricCard from '@/components/MetricCard.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import RecentManagementReviewStepsView from '@/models/ManagementReviewStep/views/RecentManagementReviewStepsView.vue'
import CreateManuscriptDialog from '@/models/ManuscriptRecord/components/CreateManuscriptDialog.vue'
import ManuscriptList from '@/models/ManuscriptRecord/components/ManuscriptList.vue'
import NoManuscriptExistsDiv from '@/models/ManuscriptRecord/components/NoManuscriptExistsDiv.vue'
import CreatePublicationDialog from '@/models/Publication/components/CreatePublicationDialog.vue'
import NoPublicationExistDiv from '@/models/Publication/components/NoPublicationExistDiv.vue'
import PublicationList from '@/models/Publication/components/PublicationList.vue'
import ContentCard from '../components/ContentCard.vue'

const { t } = useI18n()
const manuscripts = useManuscriptStore()
const reviewSteps = useManagementReviewStepStore()
const publications = usePublicationStore()

// Tab state: URL takes precedence, but remember last choice in storage
const storedTab = useStorage('dashboard-recent-tab', 'manuscripts')
const tab = useRouteQuery('tab', storedTab.value)

// Sync to storage when tab changes
watch(tab, (newTab) => {
  storedTab.value = newTab
})

// load the latest manuscripts
onMounted(() => {
  manuscripts.getMyManuscripts(true)
  reviewSteps.getMyManagementReviewSteps(true)
  publications.getMyPublications(true)
})

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
])

// manuscript section
const showCreateManuscript = ref(false)

// publication section
const showCreatePublication = ref(false)
</script>

<template>
  <MainPageLayout icon="mdi-view-dashboard" :title="$t('common.dashboard')">
    <div class="q-pa-md">
      <div class="row">
        <div
          v-for="card in data"
          :key="card.title"
          class="col-12 col-sm-4 q-pa-sm"
        >
          <MetricCard
            :title="card.title"
            :value="card.value"
            :subtitle="card.subtitle"
            :to="card.to"
          />
        </div>
      </div>

      <!-- Quick Help Guide Cards -->
      <div class="q-pa-none q-mb-md">
        <div class="text-h6 text-weight-medium text-grey-8 q-mb-sm q-ml-sm q-mt-md">
          {{ $t('dashboard.guide-cards.quick-help-title') }}
        </div>
        <div class="row">
          <div class="col-12 col-sm-6 q-pa-sm">
            <GuideCard
              :title="$t('dashboard.guide-cards.manuscript-needs-review-title')"
              :description="
                $t('dashboard.guide-cards.manuscript-needs-review-description')
              "
              :action-label="
                $t('dashboard.guide-cards.manuscript-needs-review-action')
              "
              icon="mdi-file-document-edit"
              color="primary"
              @action="showCreateManuscript = true"
            />
          </div>
          <div class="col-12 col-sm-6 q-pa-sm">
            <GuideCard
              :title="$t('dashboard.guide-cards.already-reviewed-title')"
              :description="
                $t('dashboard.guide-cards.already-reviewed-description')
              "
              :action-label="
                $t('dashboard.guide-cards.already-reviewed-action')
              "
              icon="mdi-book-check"
              color="secondary"
              @action="showCreatePublication = true"
            />
          </div>
        </div>
      </div>

      <div class="q-pa-sm">
        <ContentCard>
          <template #title>
            <q-icon name="mdi-history" color="primary" size="sm" left />
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
              <q-tab name="manuscripts" :label="$t('common.manuscripts')" />
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
                >
                  {{ reviewSteps.pendingReviewCount }}
                </q-badge>
              </q-tab>
              <q-tab name="publications" :label="$t('common.publications')" />
            </q-tabs>
            <q-separator />
          </template>

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="manuscripts" class="q-pa-none">
              <template v-if="manuscripts.empty && !manuscripts.loading">
                <NoManuscriptExistsDiv
                  :title="$t('dashboard.create-your-first-manuscript-record')"
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
                  {{ $t('my-manuscript-records.create-manuscript') }}
                </q-btn>
              </div>
            </q-tab-panel>
            <q-tab-panel name="reviews" class="q-pa-none">
              <RecentManagementReviewStepsView />
            </q-tab-panel>
            <q-tab-panel name="publications" class="q-pa-none">
              <template v-if="publications.empty">
                <NoPublicationExistDiv
                  :title="$t('dashboard.add-your-first-publication')"
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
            </q-tab-panel>
          </q-tab-panels>
        </ContentCard>
      </div>

      <!-- Dialogs (outside tabs so they work from any tab) -->
      <CreateManuscriptDialog
        v-if="showCreateManuscript"
        v-model="showCreateManuscript"
      />
      <CreatePublicationDialog
        v-if="showCreatePublication"
        v-model="showCreatePublication"
      />
    </div>
  </MainPageLayout>
</template>
