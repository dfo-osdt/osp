<script setup lang="ts">
import type { Media, MediaResource, MediaResourceList } from '@/models/Media/Media'
import ContentCard from '@/components/ContentCard.vue'
import MediaListItem from '@/models/Media/components/MediaListItem.vue'
import { useQuasar } from 'quasar'
import { type PublicationResource, PublicationService } from '../Publication'

const props = defineProps<{
  publication: PublicationResource
}>()
const $q = useQuasar()
const { t } = useI18n()
const maxFileSizeMB = 50

const publicationResourceList = ref<MediaResourceList | null>(null)
const publicationFile = ref<File | null>(null)
const uploadingFile = ref(false)
const { onFileRejected } = useFileRejectionHandler()

onMounted(async () => {
  await getFiles()
})

async function getFiles() {
  PublicationService.listPDF(props.publication.data.id).then((response) => {
    publicationResourceList.value = response
  })
}

async function upload() {
  // if there is no manuscript file, return
  if (publicationFile.value === null)
    return

  uploadingFile.value = true

  const response = await PublicationService.attachPDF(
    publicationFile.value,
    props.publication.data.id,
  )

  publicationResourceList.value?.data.push(response)

  uploadingFile.value = false
  // clear file
  publicationFile.value = null

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
    await PublicationService.deletePDF(props.publication.data.id, publicationResource.data.uuid)
    await getFiles()
  })
}

watch(publicationFile, () => {
  if (publicationFile.value) {
    upload()
  }
})
</script>

<template>
  <ContentCard class="q-mb-md" secondary>
    <template #title>
      {{
        $t('publication-page.attach-publication')
      }}
    </template>
    <p>
      {{ $t('publication-page.attach-pub-details') }}
    </p>
    <template v-if="publicationResourceList?.data">
      <q-card outlined class="q-mb-md">
        <q-list separator>
          <MediaListItem
            v-for="publicationResource in publicationResourceList.data"
            :key="publicationResource.data.uuid"
            :media="publicationResource"
            :download-url="`api/publications/${props.publication.data.id}/files/${publicationResource.data.uuid}?download=true`"
            @delete="deleteFile"
          />
        </q-list>
      </q-card>
    </template>
    <q-file
      v-if="publication?.can?.update"
      v-model="publicationFile"
      outlined
      use-chips
      :label="
        publicationResourceList?.data
          ? 'Upload a new version of the publication'
          : 'Upload the publication'
      "
      :hint="t('mrf.upload-hint', { max: maxFileSizeMB })"
      accept="application/pdf"
      :max-file-size="maxFileSizeMB * 1e6"
      counter
      :loading="uploadingFile"
      @rejected="onFileRejected"
    >
      <template #prepend>
        <q-icon name="mdi-file-pdf-box" />
      </template>
      <template #append>
        <q-btn
          color="primary"
          :loading="uploadingFile"
          :disable="!publicationFile"
          :label="$t('common.upload')"
          @click="upload"
        />
      </template>
    </q-file>
  </ContentCard>
</template>

<style scoped>

</style>
