<script setup lang="ts">
import type { FormSectionStatus } from '@/components/FormSectionStatusIcon.vue'
import type { AuthorResource } from '@/models/Author/Author'
import ContentCard from '@/components/ContentCard.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'
import { type ManuscriptAuthorResource, ManuscriptAuthorService } from '@/models/ManuscriptAuthor/ManuscriptAuthor'
import { useQuasar } from 'quasar'
import { type ManuscriptPeerReviewerResource, ManuscriptPeerReviewerService } from '../ManuscriptPeerReviewer'
import AddManuscriptPeerReviewerDialog from './AddManuscriptPeerReviewerDialog.vue'
import ManuscriptPeerReviewerChip from './ManuscriptPeerReviewerChip.vue'

const { manuscriptRecordId, readonly = false, manuscriptAuthors = [] } = defineProps<{
  manuscriptRecordId: number
  readonly?: boolean
  manuscriptAuthors?: ManuscriptAuthorResource[]
}>()

const manuscriptPeerReviewers = ref<ManuscriptPeerReviewerResource[]>([])
const loading = ref(true)
const showAddDialog = ref(false)

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
  return 'complete'
})

defineExpose({
  sectionStatus,
  manuscriptPeerReviewers,
})

const $q = useQuasar()
const { t } = useI18n()

async function getManuscriptPeerReviewers() {
  loading.value = true
  const list = await ManuscriptPeerReviewerService.list(manuscriptRecordId)
  manuscriptPeerReviewers.value = list.data
  loading.value = false
}

async function peerReviewerAdded() {
  await getManuscriptPeerReviewers()
  showAddDialog.value = false
}

async function deleteManuscriptPeerReviewer(item: ManuscriptPeerReviewerResource) {
  $q.dialog({
    title: t('mannage-peer-review.delete-peer-reviewer'),
    message: t('manage-peer-review.delete-peer-reviewer-confirm'),
    ok: {
      label: t('common.yes'),
      color: 'primary',
    },
    cancel: {
      label: t('common.no'),
      color: 'primary',
    },
  }).onOk(async () => {
    await ManuscriptPeerReviewerService.delete(item.data)
    await getManuscriptPeerReviewers()
  })
}

onMounted(async () => {
  await getManuscriptPeerReviewers()
})

const authors = computed<AuthorResource[]>(() => {
  const reviewers = manuscriptPeerReviewers.value
    .filter(item => item.data.author !== undefined)
    .map(item => item.data.author as AuthorResource)
  const authors = manuscriptAuthors
    .filter(item => item.data.author !== undefined)
    .map(item => item.data.author as AuthorResource)
  return authors.concat(reviewers)
})
</script>

<template>
  <ContentCard>
    <template #title>
      {{ $t('manage-peer-review.title') }}
    </template>
    <template #title-right>
      <FormSectionStatusIcon :status="sectionStatus" />
    </template>
    <p>
      {{ $t('manage-peer-review.description') }}
    </p>
    <q-field
      :label="$t('manage-peer-review.peer-reviewer')"
      outlined
      stack-label
      :readonly="readonly"
      bg-color="white"
      :loading="loading"
      bottom-slots
    >
      <template #control>
        <div class="self-center full-width no-outline" tabindex="0">
          <template v-if="manuscriptPeerReviewers.length > 0">
            <ManuscriptPeerReviewerChip
              v-for="item in manuscriptPeerReviewers"
              :key="item.data.id"
              :manuscript-peer-reviewer="item"
              :readonly="readonly"
              @delete="deleteManuscriptPeerReviewer(item)"
            />
          </template>
          <template v-else>
            <span>{{ $t('manage-peer-review.no-reviewers') }}</span>
          </template>
        </div>
      </template>
      <template v-if="!readonly" #append>
        <q-btn
          color="primary"
          icon="mdi-plus"
          round
          size="sm"
          :class="manuscriptPeerReviewers.length === 0 ? '' : 'q-mt-auto'"
          @click="showAddDialog = true"
        >
          <q-tooltip>
            {{ $t('manage-peer-review.add-peer-reviewer') }}
          </q-tooltip>
        </q-btn>
      </template>
    </q-field>
    <AddManuscriptPeerReviewerDialog
      v-if="showAddDialog"
      v-model="showAddDialog"
      :manuscript-record-id="manuscriptRecordId"
      :disabled-authors="authors"
      @added="peerReviewerAdded"
    />
  </ContentCard>
</template>

<style scoped>

</style>
