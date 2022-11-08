<template>
    <base-dialog title="Add an author to this publication">
        <q-form @submit="addPublicationAuthor">
            <div
                class="q-mx-md q-mt-lg text-body1 text-primary text-weight-medium"
            >
                Author
            </div>
            <p class="q-ma-md">
                Search for the author you'd want to add using their name or
                professional email. If you cannot find the author you are
                looking for, or their affiliation or email has changed, you can
                create a new author record.
            </p>
            <author-select
                v-model="authorId"
                class="q-ma-md"
                :disabled-author-ids="disabledAuthorIds"
                :rules="[(val: number|null) => val !== null || 'Author is required']"
            />
            <div class="q-mx-md q-mt-lg">
                <div class="text-body1 text-primary text-weight-medium">
                    Corresponding Author
                </div>
                <p>
                    The corresponding author communicates with the journal(s)
                    and is responsible for managing editorial issues and the
                    manuscript's final format and content. This person is not
                    necessarily the "first" author, which could have different
                    meanings in different fields. For example, it could be a
                    first or the last author or the person overseeing the
                    publication process and, often, responsible for conceiving,
                    supporting, and managing the project.
                </p>
                <p class="text-primary text-body1">
                    Is a corresponding author?
                </p>
                <q-option-group
                    v-model="isCorresponding"
                    :options="isCorrespondingOptions"
                    inline
                ></q-option-group>
                <div class="flex justify-end">
                    <q-btn
                        color="primary"
                        label="Add"
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
import BaseDialog from '@/components/BaseDialog.vue';
import {
    PublicationAuthorResource,
    PublicationAuthorService,
} from '../PublicationAuthor';

const props = withDefaults(
    defineProps<{
        publicationId: number;
        currentAuthors?: PublicationAuthorResource[];
    }>(),
    {
        currentAuthors: () => [],
    }
);

const emit = defineEmits<{
    (event: 'added', payload: PublicationAuthorResource): void;
}>();

const disabledAuthorIds = computed(() =>
    props.currentAuthors.map((author) => author.data.author_id)
);

const authorId = ref<number | null>(null);
const isCorresponding = ref(false);
const isCorrespondingOptions = [
    { label: 'Yes', value: true },
    { label: 'No', value: false },
];

async function addPublicationAuthor() {
    console.log('adding author..');
    if (authorId.value === null) {
        return;
    }
    await PublicationAuthorService.create(
        props.publicationId,
        authorId.value,
        isCorresponding.value
    ).then((author) => {
        emit('added', author);
    });
}
</script>

<style scoped></style>
