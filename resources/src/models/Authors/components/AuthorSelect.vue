<template>
    <q-select
        ref="authorSelect"
        v-model="selectedAuthor"
        :options="authors.data"
        :option-value="optionValue"
        :option-disable="optionDisable"
        :option-label="optionLabel"
        label="Author"
        :loading="authorsLoading"
        use-input
        stack-label
        outlined
        hint="Start typing to search for an author (first name, last name, or email)"
        @filter="filterAuthors"
    >
        <template #no-option>
            <q-item>
                <q-item-section class="text-grey">
                    <template v-if="lastSearchTerm === ''">
                        Start typing to search for an author
                    </template>
                    <template v-else>
                        No results found for
                        <strong>{{ lastSearchTerm }}</strong>
                    </template>
                </q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable @click="showCreateAuthorDialog = true">
                <q-item-section>
                    Can't find the author you're looking for?
                </q-item-section>
                <q-item-section side>
                    <q-btn
                        icon="mdi-plus"
                        color="primary"
                        size="sm"
                        round
                        @click="showCreateAuthorDialog = true"
                    >
                        <q-tooltip class="text-body2"
                            >Add a new author</q-tooltip
                        >
                    </q-btn>
                </q-item-section>
            </q-item>
        </template>
        <template #after-options>
            <q-separator />
            <q-item clickable @click="showCreateAuthorDialog = true">
                <q-item-section>
                    Not the author you're looking for?
                </q-item-section>
                <q-item-section side>
                    <q-btn
                        icon="mdi-plus"
                        color="primary"
                        size="sm"
                        round
                        @click="showCreateAuthorDialog = true"
                    >
                        <q-tooltip class="text-body2"
                            >Add a new author</q-tooltip
                        >
                    </q-btn>
                </q-item-section>
            </q-item>
        </template>
        <create-author-dialog
            v-if="showCreateAuthorDialog"
            v-model="showCreateAuthorDialog"
            @created="createdAuthor"
        />
    </q-select>
</template>

<script setup lang="ts">
import { QSelect } from 'quasar';
import { AuthorResource, AuthorResourceList, AuthorService } from '../Author';
import CreateAuthorDialog from './CreateAuthorDialog.vue';

const authorSelect = ref<QSelect | null>(null);

const props = withDefaults(
    defineProps<{
        modelValue: number | null;
        disabledAuthorIds?: number[];
    }>(),
    {
        disabledAuthorIds: () => [],
    }
);

const authors = ref<AuthorResourceList>({ data: [] });
const selectedAuthor = ref<AuthorResource | null>(null);
const lastSearchTerm = ref('');
const authorsLoading = ref(false);
const showCreateAuthorDialog = ref(false);

const emit = defineEmits(['update:modelValue']);
watch(selectedAuthor, (author) => {
    if (author) {
        emit('update:modelValue', author.data.id);
    }
});

const filterAuthors = async (val: string, update, abort) => {
    lastSearchTerm.value = val;
    update(async () => {
        if (val !== '') {
            const needle = val.toLowerCase();
            authorsLoading.value = true;
            await AuthorService.list(`limit=10&filter[search]=${needle}`).then(
                (response) => {
                    authors.value = response;
                }
            );
            authorsLoading.value = false;
        }
    });
};

function createdAuthor(item: AuthorResource) {
    authorSelect.value?.updateInputValue('', true);
    selectedAuthor.value = item;
    showCreateAuthorDialog.value = false;
}

function optionValue(item: AuthorResource) {
    return item.data.id;
}
function optionLabel(item: AuthorResource) {
    const { first_name, last_name, email } = item.data;
    return `${first_name} ${last_name} (${email})`;
}
function optionDisable(item: AuthorResource) {
    return props.disabledAuthorIds.includes(item.data.id);
}
</script>

<style scoped></style>
