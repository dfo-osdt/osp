<template>
    <div>
        <q-select
            v-model="selectedAuthor"
            :options="authors.data"
            :option-value="optionValue"
            :option-label="optionLabel"
            :option-disable="optionDisable"
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
            </template>
        </q-select>
    </div>
</template>

<script setup lang="ts">
import { AuthorResource, AuthorResourceList, AuthorService } from '../Authors';

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
