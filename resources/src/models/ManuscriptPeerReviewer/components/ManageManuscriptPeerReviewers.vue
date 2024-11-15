<script setup lang="ts">
import type { FormSectionStatus } from '@/components/FormSectionStatusIcon.vue'
import ContentCard from '@/components/ContentCard.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'
import { type ManuscriptPeerReviewerResource, ManuscriptPeerReviewerService } from '../ManuscriptPeerReviewer'
import ManuscriptPeerReviewerChip from './ManuscriptPeerReviewerChip.vue'

const props = defineProps<{
  manuscriptRecordId: number
  readonly?: boolean
}>()

const manuscriptPeerReviewers = ref<ManuscriptPeerReviewerResource[]>([])
const loading = ref(true)

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
  return 'complete'
})

defineExpose({
  sectionStatus,
})

async function getManuscriptPeerReviewers() {
  loading.value = true
  const list = await ManuscriptPeerReviewerService.list(props.manuscriptRecordId)
  manuscriptPeerReviewers.value = list.data
  loading.value = false
}

onMounted(async () => {
  await getManuscriptPeerReviewers()
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
        >
          <q-tooltip>
            {{ $t('manage-peer-review.add-peer-reviewer') }}
          </q-tooltip>
        </q-btn>
      </template>
    </q-field>
  </ContentCard>
</template>

<style scoped>

</style>
