<template>
    <q-dialog>
        <q-card style="width: 600px; max-width: 80vw" outlined>
            <q-card-section class="bg-teal-1 text-h6 text-accent">
                Add a new author
            </q-card-section>
            <q-separator />
            <q-select
                v-model="selectedAuthor"
                :options="authors.data"
                :option-value="value"
                :option-label="label"
                label="Author"
                use-input
                stack-label
                outlined
                hint="Start typing to search for an author (first name, last name or email)"
                class="q-ma-md"
                @filter="filterAuthors"
            >
                <template #no-option>
                    <q-item>
                        <q-item-section class="text-grey">
                            No results
                        </q-item-section>
                    </q-item>
                </template>
            </q-select>
            <q-btn
                color="primary"
                label="Add"
                class="q-ma-md"
                @click="addAuthor"
            />
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import {
    AuthorResource,
    AuthorResourceList,
    AuthorService,
} from '@/models/Authors/Authors';
import { ManuscriptAuthor } from '../ManuscriptAuthor';

const authors = ref<AuthorResourceList>({ data: [] });
const selectedAuthor = ref<AuthorResource | null>(null);
const lastSearchTerm = ref('');

const filterAuthors = async (val: string, update, abort) => {
    lastSearchTerm.value = val;
    update(async () => {
        if (val !== '') {
            const needle = val.toLowerCase();
            await AuthorService.list(`limit=10&filter[search]=${needle}`).then(
                (response) => {
                    authors.value = response;
                }
            );
        }
    });
};

function value(item: AuthorResource) {
    return item.data.id;
}
function label(item: AuthorResource) {
    const { first_name, last_name, email } = item.data;
    return `${first_name} ${last_name} (${email})`;
}
</script>

<style scoped></style>
