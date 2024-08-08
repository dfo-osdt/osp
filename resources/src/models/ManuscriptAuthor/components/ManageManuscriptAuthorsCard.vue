<script setup lang="ts">
import type { Ref } from 'vue'
import { useQuasar } from 'quasar'
import type {
  ManuscriptAuthorResource,
  ManuscriptAuthorResourceList,
} from '../ManuscriptAuthor'
import {
  ManuscriptAuthorService,
} from '../ManuscriptAuthor'
import ManuscriptAuthorChip from './ManuscriptAuthorChip.vue'
import AddManuscriptAuthorDialog from './AddManuscriptAuthorDialog.vue'
import ContentCard from '@/components/ContentCard.vue'
import type {
  FormSectionStatus,
} from '@/components/FormSectionStatusIcon.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'

const props = withDefaults(
  defineProps<{
    manuscriptRecordId: number
    readonly?: boolean
  }>(),
  {
    readonly: false,
  },
)
const { t } = useI18n()
const $q = useQuasar()
const loading = ref(true)
const manuscriptAuthors: Ref<ManuscriptAuthorResourceList> = ref({ data: [] })

const hasNoAuthors = computed(() => {
  return manuscriptAuthors.value.data.length === 0
})

const hasNoCorrespondingAuthor = computed(() => {
  return (
    manuscriptAuthors.value.data.filter(
      item => item.data.is_corresponding_author,
    ).length === 0
  )
})

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
  if (hasNoAuthors.value) {
    return 'incomplete'
  }
  if (hasNoCorrespondingAuthor.value) {
    return 'error'
  }
  return 'complete'
})

defineExpose({
  sectionStatus,
})

// on mounted get the manuscript authors
onMounted(async () => {
  loadManuscriptAuthors()
})

async function loadManuscriptAuthors() {
  loading.value = true
  await ManuscriptAuthorService.list(props.manuscriptRecordId)
    .then((response) => {
      manuscriptAuthors.value = response
    })
    .catch((error) => {
      console.log(error)
    })
  loading.value = false
}

const showAddDialog = ref(false)
async function addManuscriptAuthor() {
  showAddDialog.value = true
}

function addedManuscriptAuthor(manuscriptAuthor: ManuscriptAuthorResource) {
  $q.notify({
    type: 'positive',
    color: 'primary',
    message: `${
            manuscriptAuthor.data.author?.data.first_name ?? t('common.author')
        } ${t('common.added-successfully')}`,
  })
  showAddDialog.value = false
  loadManuscriptAuthors()
}

async function toggleCorrespondingAuthor(
  item: ManuscriptAuthorResource,
  isCorresponding: boolean,
) {
  const manuscriptAuthor = item.data
  manuscriptAuthor.is_corresponding_author = isCorresponding
  await ManuscriptAuthorService.update(manuscriptAuthor)
    .then(() => {
      $q.notify({
        type: 'positive',
        color: 'primary',
        message: `${
                    manuscriptAuthor.author?.data.first_name
                    ?? t('common.author')
                } ${t('common.updated-successfully')}`,
      })
      loadManuscriptAuthors()
    })
    .catch((error) => {
      console.log(error)
    })
}

// delete manuscript author
async function deleteManuscriptAuthor(manuscriptAuthor: ManuscriptAuthorResource) {
  // confirm with the user first
  $q.dialog({
    title: t('manage-manuscript-author-card.delete-author'),
    message: t('manage-manuscript-author-card.delete-author-msg'),
    cancel: true,
  }).onOk(async () => {
    await ManuscriptAuthorService.delete(manuscriptAuthor.data)
      .then(() => {
        // reload the manuscript authors
        loadManuscriptAuthors()
      })
      .catch((error) => {
        console.log(error)
      })
  })
}
</script>

<template>
  <ContentCard>
    <template #title>
      {{
        $t('manage-manuscript-author-card.title')
      }}
    </template>
    <template #title-right>
      <FormSectionStatusIcon :status="sectionStatus" />
    </template>
    <p>
      {{ $t('manage-manuscript-author-card.instructions') }}
    </p>
    <q-field
      :label="$t('common.author', 2)"
      outlined
      stack-label
      bg-color="white"
      :loading="loading"
      :readonly="readonly"
      bottom-slots
      :error="!hasNoAuthors && hasNoCorrespondingAuthor"
    >
      <template #control>
        <div class="self-center full-width no-outline" tabindex="0">
          <template v-if="manuscriptAuthors.data.length > 0">
            <ManuscriptAuthorChip
              v-for="item in manuscriptAuthors.data"
              :key="item.data.id"
              :manuscript-author="item"
              :readonly="readonly"
              @delete:manuscript-author="
                deleteManuscriptAuthor(item)
              "
              @edit:toggle-corresponding-author="
                toggleCorrespondingAuthor(item, $event)
              "
              @click.prevent
            />
          </template>
          <template v-else>
            <span>{{ $t('common.validation.no-authors') }}</span>
          </template>
        </div>
      </template>
      <template v-if="!readonly" #append>
        <q-btn
          icon="mdi-plus"
          color="primary"
          size="sm"
          round
          :class="hasNoAuthors ? '' : 'q-mt-auto'"
          @click="addManuscriptAuthor"
        />
      </template>
      <template #error>
        <div>{{ $t('common.validation.at-least-one-author') }}</div>
      </template>
      <AddManuscriptAuthorDialog
        v-if="showAddDialog"
        v-model="showAddDialog"
        :manuscript-record-id="manuscriptRecordId"
        :current-authors="manuscriptAuthors.data"
        @added="addedManuscriptAuthor"
      />
    </q-field>
  </ContentCard>
</template>

<style scoped></style>
