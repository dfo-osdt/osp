<script setup lang="ts">
import type { ManuscriptPeerReviewerResource } from '../ManuscriptPeerReviewer'
import type { AuthorResource } from '@/models/Author/Author'
import BaseDialog from '@/components/BaseDialog.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import { ManuscriptPeerReviewerService } from '../ManuscriptPeerReviewer'

const props = withDefaults(
  defineProps<{
    manuscriptRecordId: number
    disabledAuthors?: AuthorResource[]
  }>(),
  {
    disabledAuthors: () => [],
  },
)

const emit = defineEmits<{
  (event: 'added', payload: ManuscriptPeerReviewerResource): void
}>()

const { t } = useI18n()

const disabledAuthorIds = computed(() =>
  props.disabledAuthors.map(author => author.data.id),
)

const authorId = ref<number | null>(null)

async function addManuscriptPeerReviewer() {
  if (authorId.value === null) {
    return
  }
  await ManuscriptPeerReviewerService.create(
    props.manuscriptRecordId,
    authorId.value,
  ).then((peerReviewer) => {
    emit('added', peerReviewer)
  })
}
</script>

<template>
  <BaseDialog :title="$t('manuscript-peer-review-dialog.title')">
    <q-form @submit="addManuscriptPeerReviewer">
      <div
        class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
      >
        {{ $t('common.reviewer') }}
      </div>
      <p class="q-ma-md">
        {{ $t('manuscript-peer-reviewer-dialog.instructions') }}
      </p>
      <AuthorSelect
        v-model="authorId"
        class="q-ma-md"
        :label="$t('common.reviewer')"
        :disabled-author-ids="disabledAuthorIds"
        :rules="[(val: number|null) => val !== null || t('common.required')]"
      />
      <div class="q-mx-md q-mt-lg">
        <div class="flex justify-end">
          <q-btn
            color="primary"
            :label="$t('common.add')"
            type="submit"
            class="q-ma-md"
          />
        </div>
      </div>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
