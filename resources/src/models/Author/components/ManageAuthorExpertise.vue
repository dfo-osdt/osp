<template>
    <ContentCard secondary class="q-mt-lg">
        <template #title>
            <q-icon name="mdi-book-education-outline" size="md" />
            {{ $t('manage-author-profile-view.title') }}
        </template>
        <template #title-right>
            <q-btn
                v-if="!editMode && !readOnly"
                outline
                :label="$t('common.edit')"
                icon="mdi-book-edit-outline"
                @click="editMode = !editMode"
            />
        </template>
        <p>
            {{ $t('author-expertise.info-text') }}
        </p>
        <template v-if="!editMode">
            <template v-if="expertises?.length === 0">
                <p class="q-mt-md flex justify-between">
                    <span class="text-grey-7">{{
                        $t('author-expertise.no-expertise')
                    }}</span>
                    <q-btn
                        v-if="!editMode && !readOnly"
                        :label="$t('common.edit')"
                        outline
                        rounded
                        icon="mdi-book-edit-outline"
                        class="q-ml-sm"
                        color="primary"
                        @click="editMode = !editMode"
                    />
                </p>
            </template>
            <ExpertiseChip
                v-for="expertise in expertises"
                :key="expertise.data.id"
                :model-value="expertise"
            />
        </template>
        <template v-if="editMode">
            <div>
                <ExpertiseSelect v-model="expertises" />
            </div>
            <div class="flex justify-end">
                <q-btn
                    right
                    :label="$t('common.save')"
                    color="primary"
                    @click="syncExpertises"
                />
            </div>
        </template>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { ExpertiseResource } from '@/models/Expertise/Expertise';
import ExpertiseSelect from '@/models/Expertise/components/ExpertiseSelect.vue';
import { AuthorResource, AuthorService } from '../Author';
import ExpertiseChip from '@/models/Expertise/components/ExpertiseChip.vue';
import { useQuasar } from 'quasar';

const props = defineProps<{
    authorId: number;
}>();

const expertises = ref<ExpertiseResource[] | undefined>(undefined);
const author = ref<AuthorResource | undefined>(undefined);
const editMode = ref(false);
const loading = ref(false);
const localeStore = useLocaleStore();
const locale = computed(() => localeStore.locale);

onMounted(async () => {
    author.value = await AuthorService.find(props.authorId);
    expertises.value = sortExpertise(author.value.data?.expertises);
});

const readOnly = computed(() => {
    return author.value?.can?.update === false;
});

watch(locale, () => {
    expertises.value = sortExpertise(expertises.value);
});

async function syncExpertises() {
    if (!expertises.value) return;
    loading.value = true;
    const list = await AuthorService.syncExpertises(
        props.authorId,
        expertises.value,
    );
    expertises.value = sortExpertise(list.data);
    loading.value = false;
    notify();
}

// notify user that expertise has been saved
const { t } = useI18n();
const $q = useQuasar();

function notify() {
    $q.notify({
        type: 'positive',
        message: t('author-expertise.saved'),
    });
}

function sortExpertise(expertise: ExpertiseResource[] | undefined) {
    if (!expertise) return [];
    return expertise.sort((a, b) => {
        if (a.data[`name_${locale.value}`] < b.data[`name_${locale.value}`]) {
            return -1;
        }
        if (a.data[`name_${locale.value}`] > b.data[`name_${locale.value}`]) {
            return 1;
        }
        return 0;
    });
}
</script>

<style scoped></style>
