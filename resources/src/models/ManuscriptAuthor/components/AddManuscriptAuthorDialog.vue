<template>
    <base-dialog title="Add an author to this manuscript">
        <q-form @submit="addManuscriptAuthor">
            <author-select
                v-model="authorId"
                class="q-ma-md"
                :disabled-author-ids="disabledAuthorIds"
                :rules="[(val: number|null) => val !== null || 'Author is required']"
            />
            <q-checkbox
                v-model="isCorresponding"
                label="Is a corresponding author"
                class="q-ml-sm"
            />
            <div class="flex justify-end">
                <q-btn
                    color="primary"
                    label="Add"
                    type="submit"
                    class="q-ma-md"
                />
            </div>
        </q-form>
    </base-dialog>
</template>

<script setup lang="ts">
import AuthorSelect from '@/models/Authors/components/AuthorSelect.vue';
import {
    ManuscriptAuthorResource,
    ManuscriptAuthorService,
} from '../ManuscriptAuthor';
import BaseDialog from '@/components/BaseDialog.vue';

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

async function addManuscriptAuthor() {
    console.log('adding author..');
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
