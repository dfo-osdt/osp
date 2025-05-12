<script setup lang="ts">
import type { ManuscriptRecordResource } from '@/models/ManuscriptRecord/ManuscriptRecord'
import ContentCard from '@/components/ContentCard.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import PublicationStatusSpan from '@/models/Publication/components/PublicationStatusSpan.vue'

defineProps<{
  id: number
}>()
const loading = ref(true)
const manuscript = ref<ManuscriptRecordResource | undefined>(undefined)

/**
 * this page depends on its child routes to load the manuscript record by
 * emitting an event called 'update-manuscript'
 */
function updateManuscript(updatedManuscript: ManuscriptRecordResource) {
  manuscript.value = updatedManuscript
  loading.value = false
}

// display banner if the manuscript is reviewed but still needs to be published
const showPublishBanner = computed(() => {
  if (!manuscript.value) {
    return false
  }

  return (
    manuscript.value.data.status === 'reviewed'
    || manuscript.value.data.status === 'submitted'
  )
})
</script>

<template>
  <MainPageLayout :title="$t('common.manuscript-record')">
    <template #toolbar>
      <div class="text-subtitle2 text-grey-7 q-pl-md q-py-sm">
        <span class="text-primary text-uppercase">{{ $t('common.unique-id') }}:
        </span>
        <span class="text-weight-medium">{{
          manuscript?.data.ulid
        }}</span>
      </div>
      <q-separator />
    </template>
    <q-banner
      v-if="showPublishBanner"
      class="bg-secondary text-white q-pt-lg q-pb-md"
    >
      <div class="flex justify-between items-center">
        <div class="text-subtitle1 flex items-center">
          <q-icon
            name="mdi-information-outline"
            size="md"
            class="q-mr-sm"
          />
          <span v-if="manuscript?.data.type === 'primary'">
            {{ $t('mrf.ready-to-marked-published') }}
          </span>
          <span v-if="manuscript?.data.type === 'secondary'">
            {{ $t('mrf.ready-to-submit') }}
          </span>
          <span v-if="manuscript?.data.type === 'preprint'">
            {{ $t('mrf.ready-to-submit-preprint') }}
          </span>
        </div>
        <div>
          <q-btn
            flat
            class="text-white"
            icon-right="mdi-arrow-right-thin-circle-outline"
            :to="`/manuscript/${id}/progress`"
          />
        </div>
      </div>
    </q-banner>
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="col-11 col-md-auto">
        <ContentCard
          secondary
          class="q-mt-md"
          style="position: sticky; top: 160px"
        >
          <template #title>
            {{
              $t('common.manuscript-record')
            }}
          </template>
          <q-list>
            <q-item clickable :to="`/manuscript/${id}/form`">
              <q-item-section avatar>
                <q-icon name="mdi-file-document-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('common.form')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable :to="`/manuscript/${id}/reviews`">
              <q-item-section avatar>
                <q-icon name="mdi-account-eye-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('management-review-step-view.title')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable :to="`/manuscript/${id}/progress`">
              <q-item-section avatar>
                <q-icon name="mdi-calendar-start-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('manuscript-progress-view.title')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable :to="`/manuscript/${id}/sharing`">
              <q-item-section avatar>
                <q-icon name="mdi-share-variant-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('common.sharing')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-separator inset class="q-my-md" />
            <q-item
              clickable
              :disable="
                manuscript?.data.publication === undefined
              "
              :to="`/publication/${manuscript?.data.publication?.data.id}`"
            >
              <q-item-section avatar>
                <q-icon name="mdi-newspaper-variant-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('common.publication')
                  }}
                </q-item-label>
                <q-item-label caption>
                  <span
                    v-if="
                      manuscript?.data.publication
                        === undefined
                    "
                  >{{ $t('common.pending') }}
                  </span>
                  <PublicationStatusSpan
                    v-else
                    :status="
                      manuscript?.data.publication?.data
                        .status
                    "
                  />
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </ContentCard>
      </div>
      <div class="col q-pr-md">
        <RouterView @update-manuscript="updateManuscript" />
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
