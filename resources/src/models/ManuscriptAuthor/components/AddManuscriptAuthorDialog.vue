<template>
    <base-dialog :title="$t('manuscript-author-dialog.title')">
        <q-form @submit="addManuscriptAuthor">
            <div
                class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
            >
                {{ $t('common.author') }}
            </div>
            <p class="q-ma-md">
                {{ $t('manuscript-author-dialog.instructions') }}
            </p>
            <author-select
                v-model="authorId"
                class="q-ma-md"
                :disabled-author-ids="disabledAuthorIds"
                :rules="[(val: number|null) => val !== null || 'Author is required']"
            />
            <div class="q-mx-md q-mt-lg">
                <div class="text-body1 text-primary text-weight-medium">
                    {{ $t('common.corresponding-author') }}
                </div>
                <p>
                    {{ $t('common.corresponding-author-desc') }}
                </p>
                <p class="text-primary text-body1">
                    {{ $t('common.is-a-corresponding-author') }}
                </p>
                <q-option-group
                    v-model="isCorresponding"
                    :options="isCorrespondingOptions"
                    inline
                ></q-option-group>
                <div class="flex justify-end">
                    <q-btn
                        color="primary"
                        :label="$t('common.add')"
                        type="submit"
                        class="q-ma-md"
                    />
                </div>
            </div>
        </q-form>
    </base-dialog>
</template>

<script setup lang="ts">
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue';
import {
    ManuscriptAuthorResource,
    ManuscriptAuthorService,
} from '../ManuscriptAuthor';
import BaseDialog from '@/components/BaseDialog.vue';
const { t } = useI18n();

const props = withDefaults(
    defineProps<{
        manuscriptRecordId: number;
        currentAuthors?: ManuscriptAuthorResource[];
    }>(),
    {
        currentAuthors: () => [],
    }
);

const emit = defineEmits<{
    (event: 'added', payload: ManuscriptAuthorResource): void;
}>();

const disabledAuthorIds = computed(() =>
    props.currentAuthors.map((author) => author.data.author_id)
);

const authorId = ref<number | null>(null);
const isCorresponding = ref(false);
const isCorrespondingOptions = [
    { label: t('common.yes'), value: true },
    { label: t('common.no'), value: false },
];

async function addManuscriptAuthor() {
    if (authorId.value === null) {
        return;
    }
    await ManuscriptAuthorService.create(
        props.manuscriptRecordId,
        authorId.value,
        isCorresponding.value
    ).then((author) => {
        emit('added', author);
    });
}
</script>

<style scoped></style>
