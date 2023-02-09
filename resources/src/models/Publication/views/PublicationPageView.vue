<template>
    <MainPageLayout :title="$t('publication-page.title')">
        <div v-if="publication" class="q-pa-lg">
            <div class="q-mt-md q-mb-lg row justify-between">
                <div class="col-md-8 col-12 q-mb-md">
                    <div class="text-h4 text-primary">
                        {{
                            $t('create-publication-dialog.publication-details')
                        }}
                    </div>
                    <div
                        class="text-body2 text-weight-medium text-grey-7 ellipsis-2-lines"
                    >
                        {{ publication.data.title }}
                    </div>
                    <div
                        class="text-caption text-uppercase text-weight-medium text-grey-7 ellipsis flex"
                    >
                        <div>
                            {{ publicationYear }}
                        </div>
                        <div class="q-mx-xs">|</div>
                        <div>
                            {{
                                publication.data.journal?.data.title_en ?? ' - '
                            }}
                        </div>
                        <div class="q-mx-xs">|</div>
                        <div>
                            <span>DOI: </span>
                            <DoiLink :doi="publication.data.doi" />
                        </div>
                    </div>
                </div>
                <div class="col-grow">
                    <q-card bordered flat class="q-pa-md">
                        <div class="row justify-between">
                            <div
                                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                            >
                                {{ $t('publication-page.publication-status') }}
                            </div>
                            <PublicationStatusBadge
                                :status="publication.data.status"
                            />
                        </div>
                        <q-separator class="q-my-sm" />
                        <div class="row justify-between">
                            <div
                                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                            >
                                {{ $t('common.contact') }}
                            </div>
                            <div class="text-body2 text-grey-7 q-py-xs">
                                {{
                                    publication.data.user?.data.first_name +
                                    ' ' +
                                    publication.data.user?.data.last_name
                                }}
                                ({{ publication.data.user?.data.email }})
                            </div>
                        </div>
                        <q-separator class="q-my-sm" />
                        <div class="row justify-between">
                            <div
                                class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                            >
                                {{ $t('common.manuscript-record') }}
                            </div>
                            <div class="text-body2 text-grey-7 q-py-xs">
                                <div
                                    v-if="publication.data.manuscript_record_id"
                                >
                                    <q-btn
                                        dense
                                        size="sm"
                                        flat
                                        :label="
                                            $t(
                                                'publication-page.go-to-manuscript-record'
                                            )
                                        "
                                        :to="`/manuscript/${publication.data.manuscript_record_id}/form`"
                                        icon-right="mdi-arrow-right"
                                    />
                                </div>
                                <div v-else>
                                    <q-icon
                                        name="mdi-file-document-outline"
                                        class="q-mr-sm"
                                        color="primary"
                                        size="xs"
                                    />
                                    <span class="text-grey-7">{{
                                        $t('common.not-available')
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </q-card>
                </div>
            </div>
            <ManagePublicationAuthorsCard
                secondary
                :publication-id="id"
                class="q-mb-lg"
            />
            <ContentCard class="q-mb-lg" secondary>
                <template #title>
                    <div>{{ $t('pub.general-information') }}</div>
                </template>
                <q-form ref="generalInformationForm">
                    <q-input
                        v-model="publication.data.title"
                        :label="$t('common.title')"
                        bg-color="white"
                        :disable="loading"
                        :readonly="!canEdit"
                        outlined
                        :rules="[(val) => !!val || $t('common.required')]"
                        class="q-mb-md"
                    />
                    <JournalSelect
                        v-model="publication.data.journal_id"
                        :disable="loading"
                        :readonly="!canEdit"
                        :rules="[(val: number | null) => !!val || $t('common.required')]"
                        class="q-mb-md"
                    />
                    <DoiInput
                        v-model="publication.data.doi"
                        :disable="loading"
                        :readonly="!canEdit"
                        class="q-mb-md"
                    />
                    <div class="text-body1 text-accent text-weight-medium">
                        {{ $t('create-publication-dialog.publication-dates') }}
                    </div>
                    <q-separator class="q-mb-md" />
                    <div class="row q-gutter-sm">
                        <DateInput
                            v-model="publication.data.accepted_on"
                            :label="$t('common.accepted-on')"
                            :disable="loading"
                            :readonly="!canEdit"
                            :required="publication.data.status === 'accepted'"
                            :max-date="publication.data.published_on"
                            class="q-mb-md col-grow"
                        />
                        <DateInput
                            v-if="publication.data.status === 'published'"
                            v-model="publication.data.published_on"
                            :label="$t('common.published-on')"
                            :disable="loading"
                            :readonly="!canEdit"
                            :required="publication.data.status === 'published'"
                            :min-date="publication.data.accepted_on"
                            class="q-mb-md col-grow"
                        />
                    </div>
                    <div
                        class="text-body1 q-mt-lg text-accent text-weight-medium"
                    >
                        {{ $t('create-publication-dialog.publication-access') }}
                    </div>
                    <q-separator class="q-mb-md" />
                    <q-toggle
                        v-model="publication.data.is_open_access"
                        :label="$t('common.published-as-open-access')"
                        :disable="loading"
                        :readonly="!canEdit"
                    />
                    <DateInput
                        v-if="!publication.data.is_open_access"
                        v-model="publication.data.embargoed_until"
                        :label="$t('common.embargoed-until')"
                        :required="
                            !publication.data.is_open_access &&
                            publication.data.status === 'published'
                        "
                        :disable="loading"
                        :readonly="!canEdit"
                        :min-date="publication.data.published_on"
                        class="q-mb-md"
                    />
                </q-form>
            </ContentCard>
            <ContentCard class="q-mb-md" secondary>
                <template #title>{{
                    $t('publication-page.attach-publication')
                }}</template>
                <p>
                    {{ $t('publication-page.attach-pub-details') }}
                </p>
                <template v-if="publicationResource?.data">
                    <q-card outlined class="q-mb-md">
                        <q-list>
                            <q-item>
                                <q-item-section>
                                    <q-item-label>{{
                                        publicationResource.data.file_name
                                    }}</q-item-label>
                                    <q-item-label caption>
                                        {{
                                            publicationResource.data
                                                .size_bytes / 1000
                                        }}
                                        KB uploaded on
                                        {{
                                            new Date(
                                                publicationResource.data.created_at
                                            ).toLocaleString()
                                        }}
                                    </q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-btn
                                        icon="mdi-file-download-outline"
                                        color="primary"
                                        :loading="loading"
                                        :href="`api/publications/${id}/pdf/download`"
                                    />
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
                        publicationResource?.data
                            ? 'Upload a new version of the publication'
                            : 'Upload the publication'
                    "
                    hint="Only PDF files are accepted. Maximum file size is 10MB."
                    accept="application/pdf"
                    max-file-size="10000000"
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
            <q-card-actions align="right">
                <q-btn
                    v-if="publication.data.status === 'accepted'"
                    :label="$t('publication-page.mark-as-published')"
                    color="primary"
                    icon="mdi-flag-checkered"
                    @click="markAsPublished"
                />
                <q-btn
                    v-if="canEdit"
                    :label="$t('common.save')"
                    color="primary"
                    :loading="loading"
                    @click="save"
                />
            </q-card-actions>
        </div>
        <WarnOnUnsavedChanges :is-dirty="isDirty" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import { PublicationResource, PublicationService } from '../Publication';
import { QForm, QRejectedEntry, useQuasar } from 'quasar';
import PublicationStatusBadge from '../components/PublicationStatusBadge.vue';
import ContentCard from '@/components/ContentCard.vue';
import JournalSelect from '@/models/Journal/components/JournalSelect.vue';
import DoiInput from '../components/DoiInput.vue';
import DateInput from '@/components/DateInput.vue';
import WarnOnUnsavedChanges from '@/components/WarnOnUnsavedChanges.vue';
import DoiLink from '../components/DoiLink.vue';
import ManagePublicationAuthorsCard from '@/models/PublicationAuthor/components/ManagePublicationAuthorsCard.vue';
import { MediaResource } from '@/models/Resource';

const $q = useQuasar();
const router = useRouter();
const { t } = useI18n();

const props = defineProps<{
    id: number;
}>();

// load publication data
const loading = ref(true);
const publication = ref<PublicationResource | null>(null);

/**
 * Marks the publication as published, won't take effect
 * until user saves but will be reflected in the UI and validation.
 */
function markAsPublished() {
    if (!publication.value) return;
    publication.value.data.status = 'published';
    publication.value.data.published_on = new Date()
        .toISOString()
        .substring(0, 10);
}

// on mount load publication data
onMounted(async () => {
    try {
        publication.value = await PublicationService.find(props.id);
        publicationResource.value = await PublicationService.getPDF(props.id);
    } catch (error) {
        console.log(error);
        router.push({ name: 'notFound' });
    } finally {
        loading.value = false;
    }
});

// watch if there is a change
const isDirty = ref(false);
const disableDirtyWatcher = ref(false);
watch(
    publication,
    (newVal, oldValue) => {
        if (disableDirtyWatcher.value) return;
        if (oldValue === null || !canEdit.value) return;
        isDirty.value = true;
    },
    { deep: true }
);

// permissions
const canEdit = computed(() => {
    return publication.value?.can?.update ?? false;
});

// display elements
const publicationYear = computed(() => {
    //if (publication.value?.data?.published_on === null) return 'Pending';
    return (
        publication.value?.data.published_on?.split('-')[0] ??
        t('publication-page.publication-date-pending')
    );
});

const generalInformationForm = ref<QForm | null>(null);

const save = async () => {
    if (publication.value === null) return;

    const valid = await generalInformationForm.value?.validate();
    console.log('valid', valid);

    if (!valid) return;

    loading.value = true;
    await PublicationService.update(
        publication.value.data.id,
        publication.value.data
    )
        .then((response) => {
            publication.value = response;
            $q.notify({
                type: 'positive',
                message: t('publication-page.publication-updated-successfully'),
            });
        })
        .catch((e) => {
            console.log(e);
        })
        .finally(() => {
            loading.value = false;
            isDirty.value = false;
        });
};

// file upload
const publicationResource = ref<MediaResource | null>(null);
const publicationFile = ref<File | null>(null);
const uploadingFile = ref(false);

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

async function upload() {
    // if there is no manuscript file, return
    if (publicationFile.value === null) return;

    disableDirtyWatcher.value = true;
    uploadingFile.value = true;

    const response = await PublicationService.attachPDF(
        publicationFile.value,
        props.id
    );

    publicationResource.value = response;

    uploadingFile.value = false;
    // clear file
    publicationFile.value = null;

    $q.notify({
        type: 'positive',
        color: 'primary',
        message: t('common.file-uploaded-successfully'),
    });

    // re-enable dirty watcher in 500 ms
    setTimeout(() => {
        disableDirtyWatcher.value = false;
    }, 500);
}
</script>

<style scoped></style>
