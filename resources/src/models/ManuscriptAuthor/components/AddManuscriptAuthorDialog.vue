<template>
    <q-dialog>
        <q-card style="width: 600px; max-width: 80vw" outlined>
            <q-card-section class="bg-teal-1 text-h6 text-accent">
                Add an author to this manuscript
            </q-card-section>
            <q-separator />
            <q-form>
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
            </q-form>
            <q-btn color="primary" label="Add" class="q-ma-md" />
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import AuthorSelect from '@/models/Authors/components/AuthorSelect.vue';
import { ManuscriptAuthorResource } from '../ManuscriptAuthor';

const props = withDefaults(
    defineProps<{
        currentAuthors?: ManuscriptAuthorResource[];
    }>(),
    {
        currentAuthors: () => [],
    }
);

const disabledAuthorIds = computed(() =>
    props.currentAuthors.map((author) => author.data.author_id)
);

const authorId = ref<number | null>(null);
const isCorresponding = ref(false);
</script>

<style scoped></style>
