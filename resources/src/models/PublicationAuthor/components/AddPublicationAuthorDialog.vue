<script setup lang="ts">
import type {
  PublicationAuthorResource,
} from '../PublicationAuthor'
import BaseDialog from '@/components/BaseDialog.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import {
  PublicationAuthorService,
} from '../PublicationAuthor'

const props = withDefaults(
  defineProps<{
    publicationId: number
    currentAuthors?: PublicationAuthorResource[]
  }>(),
  {
    currentAuthors: () => [],
  },
)

const emit = defineEmits<{
  (event: 'added', payload: PublicationAuthorResource): void
}>()

const { t } = useI18n()

const disabledAuthorIds = computed(() =>
  props.currentAuthors.map(author => author.data.author_id),
)

const authorId = ref<number | null>(null)
const isCorresponding = ref(false)
const isCorrespondingOptions = [
  { label: t('common.yes'), value: true },
  { label: t('common.no'), value: false },
]
const loading = ref(false)

async function addPublicationAuthor() {
  if (authorId.value === null) {
    return
  }
  loading.value = true
  await PublicationAuthorService.create(
    props.publicationId,
    authorId.value,
    isCorresponding.value,
  ).then((author) => {
    emit('added', author)
  })
}
</script>

<template>
  <BaseDialog :title="$t('add-pub-author-dialog.title')">
    <q-form @submit="addPublicationAuthor">
      <div
        class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
      >
        {{ $t('common.author') }}
      </div>
      <p class="q-ma-md">
        {{ $t('add-pub-author-dialog.dialog-help') }}
      </p>
      <AuthorSelect
        v-model="authorId"
        class="q-ma-md"
        :disabled-author-ids="disabledAuthorIds"
        :rules="[(val: number|null) => val !== null || 'Author is required']"
      />
      <div class="q-mx-md q-mt-lg">
        <div class="text-body1 text-primary text-weight-medium">
          {{ $t('common.corresponding-author') }}
        </div>
        <p>
          {{ $t('common.corresponding-author-desc') }}
        </p>
        <p class="text-primary text-body1">
          {{ $t('common.is-a-corresponding-author') }}
        </p>
        <q-option-group
          v-model="isCorresponding"
          :options="isCorrespondingOptions"
          inline
        />
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
