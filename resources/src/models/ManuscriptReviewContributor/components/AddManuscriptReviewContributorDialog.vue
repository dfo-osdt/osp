<script setup lang="ts">
import type { ManuscriptReviewContributorResource } from '../ManuscriptReviewContributor'
import type { ReviewContributorRole } from '../reviewContributorRoleOptions'
import type { AuthorResource } from '@/models/Author/Author'
import BaseDialog from '@/components/BaseDialog.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import { ManuscriptReviewContributorService } from '../ManuscriptReviewContributor'
import ReviewContributorRoleSelect from './ReviewContributorRoleSelect.vue'

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
  (event: 'added', payload: ManuscriptReviewContributorResource): void
}>()

const { t } = useI18n()

const disabledAuthorIds = computed(() =>
  props.disabledAuthors.map(author => author.data.id),
)

const authorId = ref<number | null>(null)
const role = ref<ReviewContributorRole>('reviewer')
const loading = ref(false)

async function addManuscriptReviewContributor() {
  if (authorId.value === null) {
    return
  }
  loading.value = true
  await ManuscriptReviewContributorService.create(
    props.manuscriptRecordId,
    authorId.value,
    role.value,
  ).then((reviewContributor) => {
    emit('added', reviewContributor)
  })
}
</script>

<template>
  <BaseDialog :title="$t('manuscript-review-contributor-dialog.title')">
    <q-form @submit="addManuscriptReviewContributor">
      <div
        class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
      >
        {{ $t('manage-review-contributors.contributor') }}
      </div>
      <p class="q-ma-md">
        {{ $t('manuscript-review-contributor-dialog.instructions') }}
      </p>
      <AuthorSelect
        v-model="authorId"
        class="q-ma-md"
        :label="$t('manage-review-contributors.contributor')"
        :disabled-author-ids="disabledAuthorIds"
        :rules="[(val: number|null) => val !== null || t('common.required')]"
      />
      <ReviewContributorRoleSelect
        v-model="role"
        class="q-ma-md"
      />
      <div class="q-mx-md q-mt-lg">
        <div class="flex justify-end">
          <q-btn
            color="primary"
            :label="$t('common.add')"
            type="submit"
            class="q-ma-md"
            :loading="loading"
          />
        </div>
      </div>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
