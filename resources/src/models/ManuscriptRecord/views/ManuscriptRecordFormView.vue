<template>
    <q-banner
        v-if="showPublishBanner"
        class="bg-secondary text-white q-px-md q-pt-lg q-pb-md"
    >
        <div class="flex justify-between items-center">
            <div class="text-subtitle1 flex items-center">
                <q-icon
                    name="mdi-information-outline"
                    size="md"
                    class="q-mr-sm"
                />
                <span>
                    {{ $t("mrf.ready-to-marked-published") }}
                </span>
            </div>
            <div>
                <q-btn
                    flat
                    class="text-white"
                    icon-right="mdi-arrow-right-thin-circle-outline"
                    :to="`/manuscript/${id}/progress`"
                />
            </div>
        </div>
    </q-banner>
    <div class="q-pa-lg">
        <div class="q-mt-md q-mb-lg row justify-between">
            <div class="col-8">
                <div class="text-h4 text-primary">
                    {{ $t("common.manuscript-record-form") }}
                </div>
                <div
                    v-if="manuscriptResource"
                    class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs ellipsis"
                >
                    {{ manuscriptResource.data.title }}
                </div>
            </div>
            <div v-if="manuscriptResource" class="col-grow">
                <q-card bordered flat class="q-pa-md">
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            {{ $t("common.manuscript-type") }}
                        </div>
                        <ManuscriptTypeBadge
                            :type="manuscriptResource.data.type"
                            outline
                            class="text-body2"
                        />
                    </div>
                    <q-separator class="q-my-sm" />
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            {{ $t("common.status") }}
                        </div>
                        <span>
                            <ManuscriptStatusBadge
                                :status="manuscriptResource.data.status"
                                outline
                                class="text-body2"
                            />
                            <DeleteManuscriptButton
                                v-if="manuscriptResource.can?.delete"
                                :manuscript="manuscriptResource"
                                flat
                                size="sm"
                                color="red"
                                icon="mdi-delete-outline"
                                @deleted="$router.push({ name: 'dashboard' })"
                            />
                        </span>
                    </div>
                    <q-separator class="q-my-sm" />
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            {{ $t("common.applicant") }}
                        </div>
                        <div class="text-body2 text-grey-7 q-py-xs">
                            {{
                                manuscriptResource.data.user?.data.first_name +
                                " " +
                                manuscriptResource.data.user?.data.last_name
                            }}
                            ({{ manuscriptResource.data.user?.data.email }})
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <ManageManuscriptAuthorsCard
            ref="manuscriptAuthorsCard"
            :manuscript-record-id="id"
            :readonly="isManuscriptReadOnly"
            class="q-mb-lg"
            secondary
        />
        <ContentCard class="q-mb-lg" secondary>
            <template #title>{{ $t("mrf.general-information") }}</template>
            <template #title-right>
                <FormSectionStatusIcon :status="generalSectionStatus" />
            </template>
            <div class="flex justify-end">
                <RequiredSpan
                    :is-definition="true"
                    class="text-caption text-grey-8"
                />
            </div>
            <template v-if="manuscriptResource?.data">
                <q-form ref="generalInformationForm">
                    <div class="q-mb-md">
                        <div class="q-ml-xs">
                            <div
                                class="text-body1 text-primary text-weight-medium"
                            >
                                {{ $t("mrf.manuscripts-working-title")
                                }}<RequiredSpan />
                            </div>
                            <div class="q-my-xs">
                                <p>
                                    {{ $t("mrf.working-title-text") }}
                                </p>
                            </div>
                        </div>
                        <q-input
                            id="workingTitle"
                            v-model="manuscriptResource.data.title"
                            outlined
                            bg-color="white"
                            :label="$t('mrf.manuscripts-working-title')"
                            :disable="loading"
                            :readonly="isManuscriptReadOnly"
                            :rules="[
                                (val) =>
                                    val.length > 0 || $t('common.required'),
                            ]"
                        ></q-input>
                    </div>

                    <div class="q-mb-lg">
                        <div class="q-ml-xs">
                            <div
                                class="text-body1 text-primary text-weight-medium"
                            >
                                {{ $t("common.lead-region") }}<RequiredSpan />
                            </div>
                            <div class="q-my-xs">
                                <p>
                                    {{ $t("mrf.lead-region-text") }}
                                </p>
                            </div>
                        </div>
                        <RegionSelect
                            v-model="manuscriptResource.data.region_id"
                            :label="$t('common.lead-region')"
                            outlined
                            :disable="loading"
                            :readonly="isManuscriptReadOnly"
                            bg-color="white"
                        />
                    </div>

                    <QuestionEditor
                        v-model="manuscriptResource.data.abstract"
                        :title="$t('common.abstract')"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        required
                        class="q-mb-lg"
                    >
                        <p>
                            {{ $t("mrf.copy-your-manuscripts-abstract-here") }}
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.pls"
                        :title="$t('common.plain-language-summary')"
                        :disable="loading || PLSLoading"
                        :readonly="isManuscriptReadOnly"
                        required
                        class="q-mb-md"
                    >
                        <p>
                            {{ $t("mrf.pls-text") }}
                        </p>
                        <div class="row justify-end q-mr-sm">
                            <div
                                class="text-body1 text-primary q-pt-sm q-pr-md"
                            >
                                {{ $t("mrf.pls-help-ai-text") }}
                            </div>
                            <div>
                                <q-btn
                                    class="q-mb-md"
                                    color="primary"
                                    :label="$t('mrf.generate-pls')"
                                    icon="mdi-brain"
                                    outline
                                    rounded
                                    :disable="!enablePLSPrompt"
                                    :loading="PLSLoading"
                                    @click="generatePLS"
                                >
                                </q-btn>
                                <q-tooltip
                                    v-if="!enablePLSPrompt"
                                    class="text-body2"
                                >
                                    {{ plsDisabledTooltip }}
                                </q-tooltip>
                            </div>
                        </div>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="
                            manuscriptResource.data.scientific_implications
                        "
                        :title="$t('mrf.scientific-implications')"
                        :disable="loading"
                        required
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            {{ $t("mrf.scientific-implication-text") }}
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.relevant_to"
                        :title="$t('mrf.relevant-title')"
                        :disable="loading"
                        required
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            {{ $t("mrf.relevant-text") }}
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.regions_and_species"
                        :title="$t('mrf.geographical-title')"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            {{ $t("mrf.geographical-text") }}
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.additional_information"
                        :title="$t('mrf.additional-information-of-importance')"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            {{ $t("mrf.additional-text") }}
                        </p>

                        <div class="text-body2 text-primary text-weight-medium">
                            {{ $t("mrf.potential-media-interest-question") }}
                        </div>
                        <YesNoBooleanOptionGroup
                            v-model="
                                manuscriptResource.data
                                    .potential_public_interest
                            "
                            class="q-mb-md"
                        />
                        <p>
                            {{
                                $t(
                                    "mrf.potential-media-interest-text-field-text"
                                )
                            }}
                        </p>
                    </QuestionEditor>
                </q-form>
            </template>
            <template v-else>
                <q-skeleton type="text" />
            </template>
        </ContentCard>
        <ManageFundingSourcesCard
            v-if="manuscriptResource?.data"
            :fundable-id="manuscriptResource.data.id"
            :readonly="isManuscriptReadOnly"
            fundable-type="manuscript-records"
        />
        <ManuscriptFileManagementCard
            v-if="manuscriptResource"
            ref="manuscriptFileManagementCard"
            :manuscript="manuscriptResource"
            :readonly="isManuscriptReadOnly"
            class="q-mb-lg"
            secondary
        />
        <q-card-actions
            v-if="manuscriptResource?.can?.update"
            ref="actionsSection"
            align="right"
            class="q-mb-lg"
        >
            <DeleteManuscriptButton
                v-if="manuscriptResource.can?.delete"
                class="q-mt-md"
                :manuscript="manuscriptResource"
                :label="$t('common.delete')"
                outline
                color="red"
                icon="mdi-delete-outline"
                @deleted="$router.push({ name: 'dashboard' })"
            />
            <q-btn
                class="q-mt-md"
                color="primary"
                :loading="loading"
                :label="$t('common.save')"
                @click="save"
            />
            <div class="q-ml-sm">
                <q-tooltip>
                    {{
                        canSubmit
                            ? $t("mrf.submit-tooltip")
                            : $t("mrf.submit-tooltip-disabled")
                    }}
                </q-tooltip>
                <q-btn
                    v-if="manuscriptResource.data.status === 'draft'"
                    class="q-mt-md"
                    color="primary"
                    :loading="loading"
                    :disable="!canSubmit"
                    :label="$t('common.submit')"
                    @click="submit"
                ></q-btn>
            </div>
            <SubmitManuscriptDialog
                v-model="showSubmitDialog"
                :manuscript-record-id="id"
                @submitted="onSubmitted"
            />
        </q-card-actions>
        <WarnOnUnsavedChanges :is-dirty="isDirty" />
        <q-page-sticky
            v-if="
                !isScrolling && !isManuscriptReadOnly && !actionSectionVisible
            "
            position="bottom-right"
            :offset="[18, 18]"
        >
            <q-btn
                fab
                :icon="
                    isDirty
                        ? 'mdi-content-save-alert-outline'
                        : 'mdi-content-save-outline'
                "
                color="accent"
                :loading="loading"
                @click="save"
            />
        </q-page-sticky>
    </div>
</template>

<script setup lang="ts">
import { Ref } from "vue";
import { QForm, useQuasar } from "quasar";
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from "../ManuscriptRecord";
import QuestionEditor from "@/components/QuestionEditor.vue";
import ContentCard from "@/components/ContentCard.vue";
import ManageManuscriptAuthorsCard from "@/models/ManuscriptAuthor/components/ManageManuscriptAuthorsCard.vue";
import RegionSelect from "@/models/Region/components/RegionSelect.vue";
import ManuscriptTypeBadge from "../components/ManuscriptTypeBadge.vue";
import ManuscriptStatusBadge from "../components/ManuscriptStatusBadge.vue";
import FormSectionStatusIcon from "@/components/FormSectionStatusIcon.vue";
import RequiredSpan from "@/components/RequiredSpan.vue";
import SubmitManuscriptDialog from "../components/SubmitManuscriptDialog.vue";
import WarnOnUnsavedChanges from "@/components/WarnOnUnsavedChanges.vue";
import { UtilityService } from "@/api/utils";
import ManageFundingSourcesCard from "@/models/FundingSource/components/ManageFundingSourcesCard.vue";
import DeleteManuscriptButton from "../components/DeleteManuscriptButton.vue";
import ManuscriptFileManagementCard from "../components/ManuscriptFileManagementCard.vue";
import YesNoBooleanOptionGroup from "../components/YesNoBooleanOptionGroup.vue";

const $q = useQuasar();
const router = useRouter();
const { t } = useI18n();

const generalInformationForm = ref<QForm | null>(null);

const props = defineProps<{
    id: number;
}>();

const loading = ref(true);
const manuscriptResource: Ref<ManuscriptRecordResource | null> = ref(null);
const manuscriptAuthorsCard = ref<InstanceType<
    typeof ManageManuscriptAuthorsCard
> | null>(null);
const manuscriptFileManagementCard = ref<InstanceType<
    typeof ManuscriptFileManagementCard
> | null>(null);

// watch if there is a change
const isDirty = ref(false);
const dirtyWatcherDisabled = ref(false);

watch(
    manuscriptResource,
    (newVal, oldValue) => {
        if (oldValue === null) return;
        if (dirtyWatcherDisabled.value) return;
        isDirty.value = true;
    },
    { deep: true }
);

const isManuscriptReadOnly = computed(() => {
    return manuscriptResource.value?.can?.update === false;
});

// are all required fields in the general section filled out?
const generalSectionStatus = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return "error";
    }

    // you can't save the manuscript if the title is empty
    if (manuscript.title === "") return "error";

    const complete =
        manuscript.title !== "" &&
        manuscript.abstract !== "" &&
        manuscript.pls !== "" &&
        manuscript.scientific_implications !== "" &&
        manuscript.relevant_to;

    return complete ? "complete" : "incomplete";
});

onMounted(async () => {
    await ManuscriptRecordService.find(props.id)
        .then((response) => {
            manuscriptResource.value = response;
        })
        .catch((error) => {
            if (error.status == 403) {
                $q.notify({
                    type: "negative",
                    message: t("mrf.not-authorized-text"),
                });
                router.push({ name: "notFound" });
            }
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
        });
    loading.value = false;
});

const { isScrolling } = useScroll(window);
const actionsSection = ref<HTMLElement | null>(null);
const actionSectionVisible = useElementVisibility(actionsSection);

const save = async () => {
    // check that the manuscriptResource is not null
    if (manuscriptResource.value === null) return;

    loading.value = true;
    await ManuscriptRecordService.save(manuscriptResource.value.data)
        .then((response) => {
            manuscriptResource.value = response;
            $q.notify({
                type: "positive",
                color: "primary",
                message: t("mrf.manuscript-saved-successfully"),
            });
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
            isDirty.value = false;
        });
};

// submit the manuscript
const showSubmitDialog = ref(false);
// check if the manuscript be sent for review?
const canSubmit = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return false;
    }

    if (isDirty.value) {
        return false;
    }

    // you can't submit the manuscript if the title is empty
    if (manuscript.title === "") return false;

    // you can't submit the manuscript if the general section is incomplete
    if (generalSectionStatus.value !== "complete") return false;

    // you can't submit the manuscript if there are no authors
    if (manuscriptAuthorsCard.value?.sectionStatus !== "complete") return false;

    // you can't submit the manuscript if there is no manuscript pdf
    if (manuscriptFileManagementCard.value?.sectionStatus !== "complete")
        return false;

    return true;
});

async function submit() {
    showSubmitDialog.value = true;
}

function onSubmitted(manuscript: ManuscriptRecordResource) {
    dirtyWatcherDisabled.value = true;
    manuscriptResource.value = manuscript;
    manuscriptFileManagementCard.value?.getFiles();
    showSubmitDialog.value = false;
    // wait 2 seconds and then re-enable the dirty watcher
    setTimeout(() => {
        dirtyWatcherDisabled.value = false;
    }, 2000);
}

// display banner if the manuscript is reviewed but still needs to be published
const showPublishBanner = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return false;
    }

    return (
        manuscript.status === "reviewed" || manuscript.status === "submitted"
    );
});

// PLS generation
const PLSLoading = ref(false);

const enablePLSPrompt = computed(() => {
    if (!manuscriptResource.value?.data) {
        return false;
    }

    return (
        !isManuscriptReadOnly.value &&
        (manuscriptResource.value.data.pls === "" || PLSLoading) &&
        manuscriptResource.value.data.abstract.length > 250
    );
});

const plsDisabledTooltip = computed(() => {
    if (!manuscriptResource.value?.data) {
        return "";
    }

    if (manuscriptResource.value.data.pls !== "") {
        return t("mrf.pls-already-generated-erase-it-to-generate-a-new-one");
    }

    if (manuscriptResource.value.data.abstract.length < 250) {
        return t("mrf.abstract-must-be-at-least-250-characters");
    }

    return "";
});

async function generatePLS() {
    PLSLoading.value = true;
    if (!manuscriptResource.value?.data.abstract) return;
    if (manuscriptResource.value?.data.abstract === "") return;
    manuscriptResource.value.data.pls = t(
        "mrf.generating-pls-please-be-patient"
    );
    await UtilityService.generatePls(manuscriptResource.value.data.abstract)
        .then((response) => {
            if (!manuscriptResource.value) return;
            manuscriptResource.value.data.pls = response.data.pls;
            $q.notify({
                type: "positive",
                color: "primary",
                message: t("mrf.pls-generated-successfully"),
            });
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            PLSLoading.value = false;
        });
}
</script>

<style scoped></style>
