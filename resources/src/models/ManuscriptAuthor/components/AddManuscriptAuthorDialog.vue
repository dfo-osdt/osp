<script setup lang="ts">
import type {
  ManuscriptAuthorResource,
} from '../ManuscriptAuthor'
import BaseDialog from '@/components/BaseDialog.vue'
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import {
  ManuscriptAuthorService,
} from '../ManuscriptAuthor'

const props = withDefaults(
  defineProps<{
    manuscriptRecordId: number
    currentAuthors?: ManuscriptAuthorResource[]
  }>(),
  {
    currentAuthors: () => [],
  },
)

const emit = defineEmits<{
  (event: 'added', payload: ManuscriptAuthorResource): void
}>()

const { t } = useI18n()

const disabledAuthorIds = computed(() =>
  props.currentAuthors.map(author => author.data.author_id),
)

const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null)
const authorId = ref<number | null>(null)
const isCorresponding = ref(false)
const isGroupAuthor = ref(false)
const organizationId = ref<number | null>(null)

watch(authorId, () => {
  organizationId.value = authorSelect.value?.selectedAuthor?.data.organization_id ?? null
})
const yesNoOptions = [
  { label: t('common.yes'), value: true },
  { label: t('common.no'), value: false },
]
const loading = ref(false)

async function addManuscriptAuthor() {
  if (authorId.value === null) {
    return
  }
  loading.value = true
  await ManuscriptAuthorService.create(
    props.manuscriptRecordId,
    authorId.value,
    isCorresponding.value,
    isGroupAuthor.value,
    isGroupAuthor.value ? organizationId.value : null,
  ).then((author) => {
    emit('added', author)
  })
}
</script>

<template>
  <BaseDialog :title="$t('manuscript-author-dialog.title')">
    <q-form @submit="addManuscriptAuthor">
      <div
        class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
      >
        {{ $t('common.author') }}
      </div>
      <p class="q-ma-md">
        {{ $t('manuscript-author-dialog.instructions') }}
      </p>
      <AuthorSelect
        ref="authorSelect"
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
          :options="yesNoOptions"
          inline
        />
      </div>
      <div class="q-mx-md q-mt-lg">
        <div class="text-body1 text-primary text-weight-medium">
          {{ $t('common.group-author') }}
        </div>
        <p>
          {{ $t('common.group-author-desc') }}
        </p>
        <p class="text-primary text-body1">
          {{ $t('common.is-group-author') }}
        </p>
        <q-option-group
          v-model="isGroupAuthor"
          :options="yesNoOptions"
          inline
        />
        <OrganizationSelect
          v-if="isGroupAuthor"
          :key="authorId"
          v-model="organizationId"
          class="q-mt-md"
          :rules="[(val: number|null) => val !== null || $t('common.group-author-org-required')]"
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
