<script setup lang="ts">
import type { Media, MediaResourceList } from '@/models/Resource'
import ContentCard from '@/components/ContentCard.vue'
import { type QRejectedEntry, useQuasar } from 'quasar'
import { type PublicationResource, PublicationService } from '../Publication'

const props = defineProps<{
  publication: PublicationResource
}>()
const $q = useQuasar()
const { t } = useI18n()

const publicationResourceList = ref<MediaResourceList | null>(null)
const publicationFile = ref<File | null>(null)
const uploadingFile = ref(false)

onMounted(async () => {
  await getFiles()
})

async function getFiles() {
  PublicationService.listPDF(props.publication.data.id).then((response) => {
    publicationResourceList.value = response
  })
}

function onFileRejected(rejectedEntries: QRejectedEntry[]): void {
  console.error(rejectedEntries)
  rejectedEntries.forEach((rejectedEntry) => {
    if (rejectedEntry.failedPropValidation === 'max-file-size') {
      $q.notify({
        type: 'negative',
        color: 'negative',
        message: t('common.validation.file-size-is-too-large'),
      })
    }
    else if (rejectedEntry.failedPropValidation === 'accept') {
      $q.notify({
        type: 'negative',
        color: 'negative',
        message: t('common.validation.file-type-is-not-accepted'),
      })
    }
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

  publicationResourceList.value?.data.push(response.data)

  uploadingFile.value = false
  // clear file
  publicationFile.value = null

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
    await PublicationService.deletePDF(props.publication.data.id, publicationResource.uuid)
    await getFiles()
  })
}
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
        <q-list>
          <q-item v-for="publicationResource in publicationResourceList.data" :key="publicationResource.uuid">
            <q-item-section>
              <q-item-label>
                {{
                  publicationResource.file_name
                }}
              </q-item-label>
              <q-item-label caption>
                {{
                  publicationResource.size_bytes / 1000
                }}
                KB uploaded on
                {{
                  new Date(
                    publicationResource.created_at,
                  ).toLocaleString()
                }}
              </q-item-label>
            </q-item-section>
            <q-item-section side>
              <span>
                <q-btn
                  v-if="publication.can?.update"
                  icon="mdi-delete-outline"
                  color="negative"
                  class="q-mr-sm"
                  @click="deleteFile(publicationResource)"
                />
                <q-btn
                  v-if="publication.data.can_view_pdf"
                  icon="mdi-file-download-outline"
                  color="primary"
                  :href="`api/publications/${publication.data.id}/files/${publicationResource.uuid}?download=true`"
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
      v-model="publicationFile"
      outlined
      use-chips
      :label="
        publicationResourceList?.data
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
