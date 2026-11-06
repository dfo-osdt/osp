<script setup lang="ts">
import type { ManuscriptReviewContributorResource } from '../ManuscriptReviewContributor'
import type { ReviewContributorRole } from '../reviewContributorRoleOptions'
import type { FormSectionStatus } from '@/components/FormSectionStatusIcon.vue'
import type { AuthorResource } from '@/models/Author/Author'
import type { ManuscriptAuthorResource } from '@/models/ManuscriptAuthor/ManuscriptAuthor'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'
import { ManuscriptReviewContributorService } from '../ManuscriptReviewContributor'
import AddManuscriptReviewContributorDialog from './AddManuscriptReviewContributorDialog.vue'
import ManuscriptReviewContributorChip from './ManuscriptReviewContributorChip.vue'

const { manuscriptRecordId, readonly = false, manuscriptAuthors = [] } = defineProps<{
  manuscriptRecordId: number
  readonly?: boolean
  manuscriptAuthors?: ManuscriptAuthorResource[]
}>()

const manuscriptReviewContributors = ref<ManuscriptReviewContributorResource[]>([])
const loading = ref(true)
const showAddDialog = ref(false)

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
  return 'complete'
})

defineExpose({
  sectionStatus,
  manuscriptReviewContributors,
})

const $q = useQuasar()
const { t } = useI18n()

async function getManuscriptReviewContributors() {
  loading.value = true
  const list = await ManuscriptReviewContributorService.list(manuscriptRecordId)
  manuscriptReviewContributors.value = list.data
  loading.value = false
}

async function reviewContributorAdded() {
  await getManuscriptReviewContributors()
  showAddDialog.value = false
}

async function updateRole(item: ManuscriptReviewContributorResource, role: ReviewContributorRole) {
  const manuscriptReviewContributor = item.data
  manuscriptReviewContributor.role = role
  await ManuscriptReviewContributorService.update(manuscriptReviewContributor)
    .then(() => {
      $q.notify({
        type: 'positive',
        color: 'primary',
        message: `${
          manuscriptReviewContributor.author?.data.first_name
          ?? t('manage-review-contributors.contributor')
        } ${t('common.updated-successfully')}`,
      })
    })
  await getManuscriptReviewContributors()
}

async function deleteManuscriptReviewContributor(item: ManuscriptReviewContributorResource) {
  $q.dialog({
    title: t('manage-review-contributors.delete-contributor'),
    message: t('manage-review-contributors.delete-contributor-confirm'),
    ok: {
      label: t('common.yes'),
      color: 'primary',
    },
    cancel: {
      label: t('common.no'),
      color: 'primary',
    },
  }).onOk(async () => {
    await ManuscriptReviewContributorService.delete(item.data)
    await getManuscriptReviewContributors()
  })
}

onMounted(async () => {
  await getManuscriptReviewContributors()
})

const authors = computed<AuthorResource[]>(() => {
  const contributors = manuscriptReviewContributors.value
    .filter(item => item.data.author !== undefined)
    .map(item => item.data.author as AuthorResource)
  const authors = manuscriptAuthors
    .filter(item => item.data.author !== undefined)
    .map(item => item.data.author as AuthorResource)
  return [
    ...authors,
    ...contributors,
  ]
})
</script>

<template>
  <ContentCard>
    <template #title>
      {{ $t('manage-review-contributors.title') }}
    </template>
    <template #title-right>
      <FormSectionStatusIcon :status="sectionStatus" />
    </template>
    <p>
      {{ $t('manage-review-contributors.description') }}
    </p>
    <q-field
      :label="$t('manage-review-contributors.contributors')"
      outlined
      stack-label
      :readonly="readonly"
      bg-color="white"
      :loading="loading"
      bottom-slots
    >
      <template #control>
        <div class="self-center full-width no-outline" tabindex="0">
          <template v-if="manuscriptReviewContributors.length > 0">
            <ManuscriptReviewContributorChip
              v-for="item in manuscriptReviewContributors"
              :key="item.data.id"
              :manuscript-review-contributor="item"
              :readonly="readonly"
              @delete="deleteManuscriptReviewContributor(item)"
              @update-role="updateRole(item, $event)"
            />
          </template>
          <template v-else>
            <span>{{ $t('manage-review-contributors.no-contributors') }}</span>
          </template>
        </div>
      </template>
      <template v-if="!readonly" #append>
        <q-btn
          color="primary"
          icon="mdi-plus"
          round
          size="sm"
          :class="manuscriptReviewContributors.length === 0 ? '' : 'q-mt-auto'"
          @click="showAddDialog = true"
        >
          <q-tooltip>
            {{ $t('manage-review-contributors.add-contributor') }}
          </q-tooltip>
        </q-btn>
      </template>
    </q-field>
    <AddManuscriptReviewContributorDialog
      v-if="showAddDialog"
      v-model="showAddDialog"
      :manuscript-record-id="manuscriptRecordId"
      :disabled-authors="authors"
      @added="reviewContributorAdded"
    />
  </ContentCard>
</template>

<style scoped>

</style>
