<script setup lang="ts">
import type { MediaResource, MediaResourceList } from '@/models/Media/Media'
import type { SupplementaryFileType } from '@/models/Media/supplementaryFileOptions'
import DOMPurify from 'dompurify'
import { QInput, useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import MediaListItem from '@/models/Media/components/MediaListItem.vue'
import SupplementaryFileTypeSelect from '@/models/Media/components/SupplementaryFileTypeSelect.vue'
import { type PublicationResource, PublicationService } from '../Publication'

const props = defineProps<{
  publication: PublicationResource
}>()
const $q = useQuasar()
const { t } = useI18n()
const maxFileSizeMB = import.meta.env.VITE_MAX_UPLOAD_SIZE_MB || 4

const supplementaryFileResourceList = ref<MediaResourceList | null>(null)

const supplementaryFile = ref<File | null>(null)
const fileType = ref<SupplementaryFileType | null>(null)
const descriptionInput = ref<QInput | null>(null)
const description = ref<string | null>(null)

const uploadingFile = ref(false)
const { onFileRejected } = useFileRejectionHandler()

onMounted(async () => {
  await getFiles()
})

async function getFiles() {
  PublicationService.listSupplementaryFiles(props.publication.data.id).then((response) => {
    supplementaryFileResourceList.value = response
  })
}

async function upload() {
  // if there is no manuscript file, return
  if (supplementaryFile.value === null)
    return

  // use dompurify to sanitize the description
  if (description.value) {
    const sanitizedDescription = DOMPurify.sanitize(description.value)
    description.value = sanitizedDescription
  }
  if (description.value === '') {
    description.value = null
  }

  uploadingFile.value = true

  const response = await PublicationService.attachSupplementaryFile(
    supplementaryFile.value,
    props.publication.data.id,
    fileType.value as SupplementaryFileType,
    description.value,
  )

  supplementaryFileResourceList.value?.data.unshift(response)

  uploadingFile.value = false
  // clear file
  supplementaryFile.value = null
  fileType.value = null
  description.value = null

  $q.notify({
    type: 'positive',
    color: 'primary',
    message: t('common.file-uploaded-successfully'),
  })
}

async function deleteFile(publicationResource: MediaResource) {
  $q.dialog({
    title: t('dialog.delete-publication-pdf.title'),
    message: t('dialog.delete-publication-pdf.message', {
      file: publicationResource.data.file_name,
    }),
    ok: {
      label: t('common.delete'),
      color: 'negative',
    },
    cancel: {
      label: t('common.cancel'),
      color: 'primary',
    },
  }).onOk(async () => {
    await PublicationService.deleteSupplementaryFile(props.publication.data.id, publicationResource.data.uuid)
    await getFiles()
  })
}

const descriptionRules = [
  (val: string) => val.length <= 150 || t('common.validation.must-be-less-than-x-characters', [150]),
]

const disableUpload = computed(() => {
  if (!supplementaryFile.value)
    return true
  if (!fileType.value)
    return true
  if (description.value) {
    for (const rule of descriptionRules) {
      if (rule(description.value) !== true) {
        return true
      }
    }
  }
  return false
})

const hideMrf = computed(() => {
  return props.publication.data.manuscript_record_id !== null
})

/**
 * This is used for parents components to check if the publication has an
 * external manuscript record form (MRF) attached.
 */
const hasExternalMRF = computed(() => {
  if (supplementaryFileResourceList.value?.data) {
    return supplementaryFileResourceList.value.data.some(
      media => media.data.supplementary_file_type === 'manuscript_record_form',
    )
  }
  return false
})

defineExpose({
  supplementaryFileResourceList: readonly(supplementaryFileResourceList),
  hasExternalMRF: readonly(hasExternalMRF),
})
</script>

<template>
  <ContentCard class="q-mb-md" secondary>
    <template #title>
      {{
        t('publication-page.attach-supplementary-files')
      }}
    </template>
    <p v-if="publication.can?.update">
      {{ t('publication-page.attach-supplementary-files-details') }}
    </p>
    <template v-if="supplementaryFileResourceList?.data">
      <q-card outlined class="q-mb-md">
        <q-list separator>
          <MediaListItem
            v-for="media in supplementaryFileResourceList.data"
            :key="media.data.uuid"
            :media="media"
            :download-url="`api/publications/${props.publication.data.id}/supplementary-files/${media.data.uuid}?download=true`"
            @delete="deleteFile"
          />
        </q-list>
      </q-card>
    </template>
    <template v-if="supplementaryFileResourceList?.data.length === 0">
      <q-card bordered class="q-mb-md">
        <q-card-section class="text-center">
          {{ t('publication-page.no-supplementary-files') }}
        </q-card-section>
      </q-card>
    </template>
    <q-card v-if="publication.can?.update" class="q-pa-md" flat bordered>
      <p class="text-primary">
        Upload a supplementary file for the publication
      </p>
      <div class="row q-col-gutter-md q-mb-md">
        <SupplementaryFileTypeSelect v-model="fileType" :hide-mrf="hideMrf" class="col" />
        <q-file
          v-model="supplementaryFile"
          class="col-lg-8 col-md-12"
          outlined
          use-chips
          :label="t('common.select-file')"
          :hint="t('publication-supplementary.upload-hint', { max: maxFileSizeMB })"
          accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
          :max-file-size="maxFileSizeMB * 1e6"
          counter
          :loading="uploadingFile"
          @rejected="onFileRejected"
        >
          <template #prepend>
            <q-icon name="mdi-file-document-outline" />
          </template>
        </q-file>
      </div>
      <div class="row q-mb-md">
        <QInput
          ref="descriptionInput"
          v-model="description"
          class="col"
          outlined
          :rules="descriptionRules"
          :label="t('common.description')"
          :hint="t('common.optional')"
        />
      </div>
      <div class="row justify-end">
        <q-btn
          color="primary"
          :loading="uploadingFile"
          :disable="disableUpload"
          :label="t('common.upload')"
          @click="upload"
        />
      </div>
    </q-card>
  </ContentCard>
</template>

<style scoped>

</style>
