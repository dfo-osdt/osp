<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import DateInput from '@/components/DateInput.vue'
import SavePageSticky from '@/components/SavePageSticky.vue'
import WarnOnUnsavedChanges from '@/components/WarnOnUnsavedChanges.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
import JournalSelect from '@/models/Journal/components/JournalSelect.vue'
import { type ManuscriptRecordMetadataResource, ManuscriptRecordService } from '@/models/ManuscriptRecord/ManuscriptRecord'
import ManagePublicationAuthorsCard from '@/models/PublicationAuthor/components/ManagePublicationAuthorsCard.vue'
import RegionSelect from '@/models/Region/components/RegionSelect.vue'
import { QForm, useQuasar } from 'quasar'
import DoiInput from '../components/DoiInput.vue'
import DoiLink from '../components/DoiLink.vue'
import PublicationFileManagementCard from '../components/PublicationFileManagementCard.vue'
import PublicationStatusBadge from '../components/PublicationStatusBadge.vue'
import PublicationSupplementaryFileManagementCard from '../components/PublicationSupplementaryFileManagementCard.vue'
import { type PublicationResource, PublicationService } from '../Publication'

const props = defineProps<{
  id: number
}>()
const $q = useQuasar()
const router = useRouter()
const { t } = useI18n()

// load publication data
const loading = ref(true)
const publication = ref<PublicationResource | null>(null)
const manuscriptMetadata = ref<ManuscriptRecordMetadataResource | null>(null)
const generalInformationForm = ref<QForm | null>(null)

/**
 * Marks the publication as published, and save the publication.
 */
async function markAsPublished() {
  if (!publication.value)
    return

  const valid = await generalInformationForm.value?.validate()
  if (!valid)
    return

  publication.value.data.status = 'published'
  publication.value.data.published_on = new Date()
    .toISOString()
    .substring(0, 10)
  await save()
}

// on mount load publication data
onMounted(async () => {
  try {
    publication.value = await PublicationService.find(props.id)
    if (publication.value.data.manuscript_record_id === null)
      return
    manuscriptMetadata.value = await ManuscriptRecordService.metadata(publication.value.data.manuscript_record_id)
  }
  catch (error) {
    console.error(error)
    router.push({ name: 'notFound' })
  }
  finally {
    loading.value = false
  }
})

// permissions
const canEdit = computed(() => {
  return publication.value?.can?.update ?? false
})

// watch if there is a change
const isDirty = ref(false)
const disableDirtyWatcher = ref(false)

watch(
  publication,
  (newVal, oldValue) => {
    if (disableDirtyWatcher.value)
      return
    if (oldValue === null || !canEdit.value)
      return
    isDirty.value = true
  },
  { deep: true },
)

// display elements
const publicationYear = computed(() => {
  // if (publication.value?.data?.published_on === null) return 'Pending';
  return (
    publication.value?.data.published_on?.split('-')[0]
    ?? t('publication-page.publication-date-pending')
  )
})

async function save() {
  if (publication.value === null)
    return

  const valid = await generalInformationForm.value?.validate()

  if (!valid)
    return

  loading.value = true
  await PublicationService.update(
    publication.value.data.id,
    publication.value.data,
  )
    .then((response) => {
      publication.value = response
      $q.notify({
        type: 'positive',
        message: t('publication-page.publication-updated-successfully'),
      })
    })
    .catch((e) => {
      console.error(e)
    })
    .finally(() => {
      loading.value = false
      isDirty.value = false
    })
}

// scroll save logic
const saveButton = ref<HTMLButtonElement | null>(null)
const saveButtonIsVisible = useElementVisibility(saveButton)
</script>

<template>
  <MainPageLayout :title="t('publication-page.title')">
    <div v-if="publication" class="q-pa-lg">
      <div class="q-mt-md q-mb-lg row justify-between">
        <div class="col-md-8 col-12 q-mb-md">
          <div class="text-h4 text-primary">
            {{
              t('create-publication-dialog.publication-details')
            }}
          </div>
          <div
            class="text-body2 text-weight-medium text-grey-7 ellipsis-2-lines"
          >
            {{ publication.data.title }}
          </div>
          <div
            class="text-caption text-uppercase text-weight-medium text-grey-7 ellipsis flex"
          >
            <div>
              {{ publicationYear }}
            </div>
            <div class="q-mx-xs">
              |
            </div>
            <div>
              {{
                publication.data.journal?.data.title ?? ' - '
              }}
            </div>
            <div class="q-mx-xs">
              |
            </div>
            <div>
              <span>DOI: </span>
              <DoiLink :doi="publication.data.doi" />
            </div>
          </div>
        </div>
        <div class="col-grow">
          <q-card bordered flat class="q-pa-md">
            <div class="row justify-between">
              <div
                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
              >
                {{ t('publication-page.publication-status') }}
              </div>
              <PublicationStatusBadge
                :status="publication.data.status"
              />
            </div>
            <q-separator class="q-my-sm" />
            <div class="row justify-between">
              <div
                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
              >
                {{ t('common.contact') }}
              </div>
              <div class="text-body2 text-grey-7 q-py-xs">
                {{
                  `${publication.data.user?.data.first_name
                  } ${
                    publication.data.user?.data.last_name}`
                }}
                ({{ publication.data.user?.data.email }})
              </div>
            </div>
            <q-separator class="q-my-sm" />
            <div class="row justify-between">
              <div
                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
              >
                {{ t('common.manuscript-record') }}
              </div>
              <div class="text-body2 text-grey-7 q-py-xs">
                <div
                  v-if="publication.data.manuscript_record_id"
                >
                  <q-btn
                    dense
                    size="sm"
                    flat
                    :label="
                      t(
                        'publication-page.go-to-manuscript-record',
                      )
                    "
                    :to="`/manuscript/${publication.data.manuscript_record_id}/form`"
                    :disable="!manuscriptMetadata?.can?.view"
                    icon-right="mdi-arrow-right"
                  />
                </div>
                <div v-else>
                  <q-icon
                    name="mdi-file-document-outline"
                    class="q-mr-sm"
                    color="primary"
                    size="xs"
                  />
                  <span class="text-grey-7">{{
                    t('common.not-available')
                  }}</span>
                </div>
              </div>
            </div>
          </q-card>
        </div>
      </div>
      <ManagePublicationAuthorsCard
        secondary
        :publication-id="id"
        class="q-mb-lg"
        :readonly="!canEdit"
      />
      <ContentCard class="q-mb-lg" secondary>
        <template #title>
          <div>{{ t('pub.general-information') }}</div>
        </template>
        <QForm ref="generalInformationForm">
          <q-input
            v-model="publication.data.title"
            :label="t('common.title')"
            bg-color="white"
            :disable="loading"
            :readonly="!canEdit"
            outlined
            :rules="[(val) => !!val || t('common.required')]"
            class="q-mb-md"
          />
          <RegionSelect
            v-model="publication.data.region_id"
            :label="$t('common.lead-region')"
            outlined
            :disable="loading"
            :readonly="!canEdit"
            :hint="$t('publication.lead-region-hint')"
            class="q-mb-md"
          />
          <JournalSelect
            v-model="publication.data.journal_id"
            :disable="loading"
            :readonly="!canEdit"
            :rules="[
              (val: number | null) =>
                !!val || t('common.required'),
            ]"
            class="q-mb-md"
          />
          <DoiInput
            v-model="publication.data.doi"
            :disable="loading"
            :readonly="!canEdit"
            class="q-mb-md"
          />
          <div class="text-body1 text-primary text-weight-medium">
            {{ t('create-publication-dialog.publication-dates') }}
          </div>
          <q-separator class="q-mb-md" />
          <div class="row q-gutter-sm">
            <DateInput
              v-model="publication.data.accepted_on"
              :label="t('common.accepted-on')"
              :disable="loading"
              :readonly="!canEdit"
              :required="publication.data.status === 'accepted'"
              :max-date="publication.data.published_on"
              class="q-mb-md col-grow"
            />
            <DateInput
              v-if="publication.data.status === 'published'"
              v-model="publication.data.published_on"
              :label="t('common.published-on')"
              :disable="loading"
              :readonly="!canEdit"
              :required="publication.data.status === 'published'"
              :min-date="publication.data.accepted_on"
              class="q-mb-md col-grow"
            />
          </div>
          <div
            class="text-body1 q-mt-lg text-primary text-weight-medium"
          >
            {{ t('create-publication-dialog.publication-access') }}
          </div>
          <q-separator class="q-mb-md" />
          <q-toggle
            v-model="publication.data.is_open_access"
            :label="t('common.published-as-open-access')"
            :disable="loading || !canEdit"
            :readonly="!canEdit"
          />
          <DateInput
            v-if="!publication.data.is_open_access"
            v-model="publication.data.embargoed_until"
            :label="t('common.embargoed-until')"
            :required="
              !publication.data.is_open_access
                && publication.data.status === 'published'
            "
            :disable="loading"
            :readonly="!canEdit"
            :min-date="publication.data.published_on"
            class="q-mb-md"
          />
        </QForm>
      </ContentCard>
      <PublicationFileManagementCard
        v-if="publication"
        :publication="publication"
      />
      <PublicationSupplementaryFileManagementCard
        v-if="publication"
        :publication="publication"
      />
      <q-card-actions align="right">
        <q-btn
          v-if="publication.data.status === 'accepted' && canEdit"
          :label="t('publication-page.mark-as-published')"
          color="primary"
          icon="mdi-flag-checkered"
          @click="markAsPublished"
        />
        <q-btn
          v-if="canEdit"
          ref="saveButton"
          :label="t('common.save')"
          color="primary"
          :loading="loading"
          @click="save"
        />
      </q-card-actions>
    </div>
    <WarnOnUnsavedChanges :is-dirty="isDirty" />
    <SavePageSticky
      v-if="canEdit && !saveButtonIsVisible"
      :is-dirty="isDirty"
      :loading="loading"
      hide-on-scroll
      @save="save"
    />
  </MainPageLayout>
</template>

<style scoped></style>
