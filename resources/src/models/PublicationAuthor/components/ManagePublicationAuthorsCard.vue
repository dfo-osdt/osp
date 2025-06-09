<script setup lang="ts">
import type { Ref } from 'vue'
import type {
  PublicationAuthorResource,
  PublicationAuthorResourceList,
} from '@/models/PublicationAuthor/PublicationAuthor'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import AddPublicationAuthorDialog from '@/models/PublicationAuthor/components/AddPublicationAuthorDialog.vue'
import PublicationAuthorChip from '@/models/PublicationAuthor/components/PublicationAuthorChip.vue'
import {
  PublicationAuthorService,
} from '@/models/PublicationAuthor/PublicationAuthor'

const props = withDefaults(
  defineProps<{
    publicationId: number
    readonly?: boolean
  }>(),
  {
    readonly: false,
  },
)
const $q = useQuasar()
const { t } = useI18n()
const loading = ref(true)
const publicationAuthors: Ref<PublicationAuthorResourceList> = ref({
  data: [],
})

const hasNoAuthors = computed(() => {
  return publicationAuthors.value.data.length === 0
})

const hasNoCorrespondingAuthor = computed(() => {
  return (
    publicationAuthors.value.data.filter(
      item => item.data.is_corresponding_author,
    ).length === 0
  )
})

const sectionStatus = computed(() => {
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
  loadPublicationAuthors()
})

async function loadPublicationAuthors() {
  loading.value = true
  await PublicationAuthorService.list(props.publicationId)
    .then((response) => {
      publicationAuthors.value = response
    })
    .catch((error) => {
      console.error(error)
    })
  loading.value = false
}

const showAddDialog = ref(false)
async function addPublicationAuthor() {
  showAddDialog.value = true
}

function addedPublicationAuthor(publicationAuthor: PublicationAuthorResource) {
  $q.notify({
    type: 'positive',
    color: 'primary',
    message: `${
      publicationAuthor.data.author?.data.first_name ?? t('common.author')
    } ${t('common.added-successfully')}.`,
  })
  showAddDialog.value = false
  loadPublicationAuthors()
}

async function toggleCorrespondingAuthor(
  item: PublicationAuthorResource,
  isCorresponding: boolean,
) {
  const publicationAuthor = item.data
  publicationAuthor.is_corresponding_author = isCorresponding
  await PublicationAuthorService.update(publicationAuthor)
    .then(() => {
      $q.notify({
        type: 'positive',
        color: 'primary',
        message: `${
          publicationAuthor.author?.data.first_name
          ?? t('common.author')
        } ${t('common.updated-successfully')}.`,
      })
      loadPublicationAuthors()
    })
    .catch((error) => {
      console.error(error)
    })
}

// delete manuscript author
async function deletePublicationAuthor(publicationAuthor: PublicationAuthorResource) {
  // confirm with the user first
  $q.dialog({
    title: t('pub-author-dialog.delete-author'),
    message: t(
      'pub-author-dialog.are-you-sure-you-want-to-delete-this-author',
    ),
    cancel: true,
  }).onOk(async () => {
    await PublicationAuthorService.delete(publicationAuthor.data)
      .then(() => {
        // reload the manuscript authors
        loadPublicationAuthors()
      })
      .catch((error) => {
        console.error(error)
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
          <template v-if="publicationAuthors.data.length > 0">
            <PublicationAuthorChip
              v-for="item in publicationAuthors.data"
              :key="item.data.id"
              :publication-author="item"
              :readonly="readonly"
              @delete-publication-author="
                deletePublicationAuthor(item)
              "
              @edit-toggle-corresponding-author="
                toggleCorrespondingAuthor(item, $event)
              "
              @click.prevent
            />
          </template>
          <template v-else>
            <span>{{ $t('manage-pub-authors.no-authors') }}</span>
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
          @click="addPublicationAuthor"
        />
      </template>
      <template #error>
        <div>{{ $t('common.validation.at-least-one-author') }}</div>
      </template>
      <AddPublicationAuthorDialog
        v-if="showAddDialog"
        v-model="showAddDialog"
        :publication-id="publicationId"
        :current-authors="publicationAuthors.data"
        @added="addedPublicationAuthor"
      />
    </q-field>
  </ContentCard>
</template>

<style scoped></style>
