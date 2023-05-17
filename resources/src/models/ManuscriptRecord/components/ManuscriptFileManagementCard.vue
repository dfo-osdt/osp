<template>
    <ContentCard>
        <template #title>{{ $t('common.manuscript') }}</template>
        <template #title-right
            ><FormSectionStatusIcon :status="sectionStatus"
        /></template>
        <p>
            {{ $t('mrf.upload-text') }}
        </p>
        <template v-if="manuscriptFiles">
            <q-card outlined class="q-mb-md">
                <q-list separator>
                    <q-item v-for="m in manuscriptFiles.data" :key="m.uuid">
                        <q-item-section>
                            <q-item-label
                                ><q-icon
                                    v-if="m.locked"
                                    name="mdi-lock-outline"
                                    color="secondary"
                                    class="q-mr-sm"
                                />{{ m.file_name }}</q-item-label
                            >
                            <q-item-label caption>
                                {{ m.size_bytes / 1000 }}
                                {{ $t('common.kb-uploaded-on') }}
                                {{ new Date(m.created_at).toLocaleString() }}
                            </q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <span>
                                <q-btn
                                    v-if="!m.locked"
                                    icon="mdi-delete-outline"
                                    color="negative"
                                    class="q-mr-sm"
                                    outline
                                    @click="confirmDelete(m)"
                                />
                                <q-btn
                                    icon="mdi-file-download-outline"
                                    color="primary"
                                    :href="`api/manuscript-records/${manuscript.data.id}/files/${m.uuid}?download=true`"
                                />
                            </span>
                        </q-item-section>
                    </q-item>
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
                    ? $t('mrf.replace-manuscript')
                    : $t('mrf.upload-manuscript')
            "
            :hint="$t('mrf.upload-hint', { max: 10 })"
            accept="application/pdf"
            max-file-size="10000000"
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

<script setup lang="ts">
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';
import ContentCard from '@/components/ContentCard.vue';
import FormSectionStatusIcon, {
    FormSectionStatus,
} from '@/components/FormSectionStatusIcon.vue';
import { MediaResourceList, Media } from '@/models/Resource';
import { QRejectedEntry, useQuasar } from 'quasar';
import { Ref } from 'vue';

const { t } = useI18n();
const $q = useQuasar();

const props = defineProps<{
    manuscript: ManuscriptRecordResource;
}>();

const manuscriptFiles: Ref<MediaResourceList | null> = ref(null);
const manuscriptFile: Ref<File | null> = ref(null);
const uploadingFile = ref(false);

onMounted(() => {
    getFiles();
});

watch(manuscriptFile, () => {
    if (manuscriptFile.value) {
        upload();
    }
});

async function getFiles() {
    manuscriptFiles.value = await ManuscriptRecordService.listPDFs(
        props.manuscript.data.id
    );
}

const sectionStatus: ComputedRef<FormSectionStatus> = computed(() => {
    if (!manuscriptFiles.value) return 'incomplete';
    if (manuscriptFiles.value.data.length > 0) return 'complete';
    return 'incomplete';
});

defineExpose({
    sectionStatus,
    getFiles,
});

function onFileRejected(rejectedEntries: QRejectedEntry[]): void {
    console.log(rejectedEntries);
    rejectedEntries.forEach((rejectedEntry) => {
        if (rejectedEntry.failedPropValidation === 'max-file-size') {
            $q.notify({
                type: 'negative',
                color: 'negative',
                message: t('common.validation.file-size-is-too-large'),
            });
        } else if (rejectedEntry.failedPropValidation === 'accept') {
            $q.notify({
                type: 'negative',
                color: 'negative',
                message: t('common.validation.file-type-is-not-accepted'),
            });
        }
    });
}

const displayFileUpload = computed(() => {
    const m = props.manuscript.data;
    if (!m) return false;
    return (
        (m.can_attach_manuscript && manuscriptFiles.value?.data.length === 0) ||
        (m.status !== 'draft' && m.can_attach_manuscript)
    );
});

async function upload() {
    // if there is no manuscript file, return
    if (manuscriptFile.value === null) return;

    uploadingFile.value = true;

    const response = await ManuscriptRecordService.attachPDF(
        manuscriptFile.value,
        props.manuscript.data.id
    );

    uploadingFile.value = false;
    // clear file
    manuscriptFile.value = null;

    $q.notify({
        type: 'positive',
        color: 'primary',
        message: t('common.file-uploaded-successfully', {
            file: response.data.file_name,
        }),
    });

    await getFiles();
}

async function confirmDelete(manuscriptFile: Media) {
    $q.dialog({
        title: t('dialog.delete-manuscript-pdf.title'),
        message: t('dialog.delete-manuscript-pdf.message', {
            file: manuscriptFile.file_name,
        }),
        cancel: true,
        persistent: false,
    }).onOk(async () => {
        deleteFile(manuscriptFile);
    });
}

async function deleteFile(manuscriptFile: Media) {
    const m = props.manuscript.data;
    const deleted = await ManuscriptRecordService.deletePDF(
        m.id,
        manuscriptFile.uuid
    );
    if (deleted) {
        $q.notify({
            message: t('dialog.delete-manuscript-pdf.manuscript-pdf-deleted'),
            type: 'positive',
            icon: 'mdi-check',
        });
    }
    await getFiles();
}
</script>

<style scoped></style>
