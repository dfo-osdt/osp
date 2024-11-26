<script setup lang="ts">
import type { Media, MediaResourceList, SupplementaryFileType } from '@/models/Resource'
import ContentCard from '@/components/ContentCard.vue'
import DOMPurify from 'dompurify'
import { useQuasar } from 'quasar'
import { type PublicationResource, PublicationService } from '../Publication'

const props = defineProps<{
  publication: PublicationResource
}>()
const $q = useQuasar()
const { t } = useI18n()

const supplementaryFileResourceList = ref<MediaResourceList | null>(null)

const supplementaryFile = ref<File | null>(null)
const fileType = ref<SupplementaryFileType>('author_agreement')
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
    fileType.value,
    description.value,
  )

  supplementaryFileResourceList.value?.data.push(response)

  uploadingFile.value = false
  // clear file
  supplementaryFile.value = null

  $q.notify({
    type: 'positive',
    color: 'primary',
    message: t('common.file-uploaded-successfully'),
  })
}

async function deleteFile(publicationResource: Media) {
  $q.dialog({
    title: t('dialog.delete-publication-pdf.title'),
    message: t('dialog.delete-publication-pdf.message', {
      file: publicationResource.file_name,
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
    await PublicationService.deleteSupplementaryFile(props.publication.data.id, publicationResource.uuid)
    await getFiles()
  })
}
</script>

<template>
  <ContentCard class="q-mb-md" secondary>
    <template #title>
      {{
        $t('publication-page.attach-supplementary-files')
      }}
    </template>
    <p>
      {{ $t('publication-page.attach-supplementary-files-details') }}
    </p>
    <template v-if="supplementaryFileResourceList?.data">
      <q-card outlined class="q-mb-md">
        <q-list separator>
          <q-item v-for="publicationResource in supplementaryFileResourceList.data" :key="publicationResource.data.uuid">
            <q-item-section>
              <q-item-label>
                {{
                  publicationResource.data.file_name
                }}
              </q-item-label>
              <q-item-label caption>
                {{
                  publicationResource.data.size_bytes / 1000
                }}
                {{ $t('common.kb-uploaded-on') }}
                {{
                  new Date(
                    publicationResource.data.created_at,
                  ).toLocaleString()
                }}
              </q-item-label>
            </q-item-section>
            <q-item-section side>
              <span>
                <q-btn
                  v-if="publicationResource.can?.delete"
                  icon="mdi-delete-outline"
                  color="negative"
                  outline
                  class="q-mr-sm"
                  @click="deleteFile(publicationResource.data)"
                />
                <q-btn
                  v-if="publicationResource.can?.download"
                  icon="mdi-file-download-outline"
                  color="primary"
                  :href="`api/publications/${publication.data.id}/files/${publicationResource.data.uuid}?download=true`"
                >
                  <q-tooltip>
                    {{ $t('common.download') }}
                  </q-tooltip>
                </q-btn>
                <div v-else>
                  <span class="q-mr-sm">{{
                    $t(
                      'common.publication-under-embargo',
                    )
                  }}</span>
                  <q-icon
                    name="mdi-download-lock"
                    size="sm"
                  />
                </div>
              </span>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card>
    </template>
    <q-file
      v-if="publication?.can?.update"
      v-model="supplementaryFile"
      outlined
      use-chips
      :label="
        supplementaryFileResourceList?.data
          ? 'Upload a new version of the publication'
          : 'Upload the publication'
      "
      hint="Only PDF files are accepted. Maximum file size is 50MB."
      accept="application/pdf"
      max-file-size="50000000"
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
          :disable="!supplementaryFile"
          :label="$t('common.upload')"
          @click="upload"
        />
      </template>
    </q-file>
  </ContentCard>
</template>

<style scoped>

</style>
