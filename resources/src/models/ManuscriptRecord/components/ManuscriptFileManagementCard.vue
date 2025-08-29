<script setup lang="ts">
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import type { FormSectionStatus } from '@/components/FormSectionStatusIcon.vue'
import type { MediaResource, MediaResourceList } from '@/models/Media/Media'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue'
import MediaListItem from '@/models/Media/components/MediaListItem.vue'
import {

  ManuscriptRecordService,
} from '../ManuscriptRecord'

const props = defineProps<{
  manuscript: ManuscriptRecordResource
}>()
const { t } = useI18n()
const $q = useQuasar()
const { onFileRejected } = useFileRejectionHandler()
const maxFileSizeMB = import.meta.env.VITE_MAX_UPLOAD_SIZE_MB || 4

const manuscriptFiles: Ref<MediaResourceList | null> = ref(null)
const manuscriptFile: Ref<File | null> = ref(null)
const uploadingFile = ref(false)

onMounted(() => {
  getFiles()
})

watch(manuscriptFile, () => {
  if (manuscriptFile.value) {
    upload()
  }
})

async function getFiles() {
  manuscriptFiles.value = await ManuscriptRecordService.listPDFs(
    props.manuscript.data.id,
  )
}

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
  if (!manuscriptFiles.value)
    return 'incomplete'
  if (manuscriptFiles.value.data.length > 0)
    return 'complete'
  return 'incomplete'
})

defineExpose({
  sectionStatus,
  getFiles,
})

const displayFileUpload = computed(() => {
  const m = props.manuscript.data
  if (!m)
    return false
  return (
    (m.can_attach_manuscript && manuscriptFiles.value?.data.length === 0)
    || (m.status !== 'draft' && m.can_attach_manuscript)
  )
})

async function upload() {
  // if there is no manuscript file, return
  if (manuscriptFile.value === null)
    return

  uploadingFile.value = true

  const response = await ManuscriptRecordService.attachPDF(
    manuscriptFile.value,
    props.manuscript.data.id,
  )

  uploadingFile.value = false
  // clear file
  manuscriptFile.value = null

  $q.notify({
    type: 'positive',
    color: 'primary',
    message: t('common.file-uploaded-successfully', {
      file: response.data.file_name,
    }),
  })

  await getFiles()
}

async function confirmDelete(manuscriptFile: MediaResource) {
  $q.dialog({
    title: t('dialog.delete-manuscript-pdf.title'),
    message: t('dialog.delete-manuscript-pdf.message', {
      file: manuscriptFile.data.file_name,
    }),
    cancel: true,
    persistent: false,
  }).onOk(async () => {
    deleteFile(manuscriptFile)
  })
}

async function deleteFile(manuscriptFile: MediaResource) {
  const m = props.manuscript.data
  const deleted = await ManuscriptRecordService.deletePDF(
    m.id,
    manuscriptFile.data.uuid,
  )
  if (deleted) {
    $q.notify({
      message: t('dialog.delete-manuscript-pdf.manuscript-pdf-deleted'),
      type: 'positive',
      icon: 'mdi-check',
    })
  }
  await getFiles()
}
</script>

<template>
  <ContentCard>
    <template #title>
      {{ t('common.manuscript') }}
    </template>
    <template #title-right>
      <FormSectionStatusIcon :status="sectionStatus" />
    </template>
    <p>
      {{ t('mrf.upload-text') }}
    </p>
    <template v-if="manuscriptFiles">
      <q-card outlined class="q-mb-md">
        <q-list separator>
          <MediaListItem
            v-for="(m, index) in manuscriptFiles.data"
            :key="m.data.uuid"
            :media="m"
            :download-url="`api/manuscript-records/${manuscript.data.id}/files/${m.data.uuid}?download=true`"
            @delete="confirmDelete"
          >
            <template v-if="index !== 0" #prepend>
              <q-item-section avatar>
                <q-icon name="mdi-history" color="secondary">
                  <q-tooltip>
                    {{
                      t('common.manuscript-previous-version')
                    }}
                  </q-tooltip>
                </q-icon>
              </q-item-section>
            </template>
          </MediaListItem>
        </q-list>
      </q-card>
    </template>
    <q-file
      v-if="displayFileUpload"
      v-model="manuscriptFile"
      outlined
      use-chips
      :label="
        manuscriptFiles?.data.length ?? 0 > 0
          ? t('mrf.replace-manuscript')
          : t('mrf.upload-manuscript')
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
    </q-file>
  </ContentCard>
</template>

<style scoped></style>
