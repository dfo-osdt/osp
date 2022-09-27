<template>
    <q-chip
        clickable
        :removable="removable"
        color="teal-2"
        @remove="emit('delete:manuscriptAuthor')"
    >
        <q-avatar
            v-if="manuscriptAuthor.data.is_corresponding_author"
            icon="mdi-at"
            color="primary"
            text-color="white"
        />
        {{ name }}
        <q-menu>
            <q-list dense separator>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar icon="mdi-bank" text-color="primary" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>
                            {{
                                manuscriptAuthor.data.organization?.data.name_en
                            }}
                        </q-item-label>
                    </q-item-section>
                </q-item>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar icon="mdi-email" text-color="primary" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>
                            <a
                                class="text-primary"
                                :href="`mailto:${manuscriptAuthor.data.author?.data.email}`"
                                >{{
                                    manuscriptAuthor.data.author?.data.email
                                }}</a
                            >
                        </q-item-label>
                    </q-item-section>
                    <q-item-section v-if="isSupported" side>
                        <q-btn
                            v-if="!copied"
                            icon="mdi-content-copy"
                            round
                            flat
                            size="sm"
                            @click="
                                copy(
                                    manuscriptAuthor.data.author?.data.email ??
                                        ''
                                )
                            "
                        >
                            <q-tooltip>Copy to clipboard</q-tooltip>
                        </q-btn>
                        <div v-else class="text-caption">Copied</div>
                    </q-item-section>
                </q-item>
                <q-item
                    v-if="manuscriptAuthor.data.author?.data.orcid"
                    clickable
                >
                    <q-item-section avatar>
                        <orcid-avatar />
                    </q-item-section>
                    <q-item-section>
                        <a
                            class="text-primary"
                            :href="`https://orcid.org/${manuscriptAuthor.data.author?.data.orcid}`"
                            target="_blank"
                            >{{ manuscriptAuthor.data.author?.data.orcid }}</a
                        >
                    </q-item-section>
                </q-item>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar icon="mdi-at" text-color="primary" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label
                            :class="correspondingAuthor ? '' : 'text-grey-5'"
                        >
                            Corresponding Author</q-item-label
                        >
                    </q-item-section>
                    <q-item-section v-if="!readonly" side
                        ><q-toggle
                            v-model="correspondingAuthor"
                            @update:model-value="
                                $emit(
                                    'edit:toggleCorrespondingAuthor',
                                    correspondingAuthor
                                )
                            "
                            ><q-tooltip
                                >Toggles whether this is a corresponding
                                author</q-tooltip
                            ></q-toggle
                        ></q-item-section
                    >
                </q-item>
            </q-list>
        </q-menu>
    </q-chip>
</template>

<script setup lang="ts">
import { ManuscriptAuthorResource } from '../ManuscriptAuthor';
import OrcidAvatar from '../../../components/OrcidAvatar.vue';

const { copy, copied, isSupported } = useClipboard();

const props = withDefaults(
    defineProps<{
        manuscriptAuthor: ManuscriptAuthorResource;
        readonly?: boolean;
    }>(),
    {
        readonly: false,
    }
);

const correspondingAuthor = ref(
    props.manuscriptAuthor.data.is_corresponding_author
);

const name = computed(() => {
    return `${props.manuscriptAuthor.data.author?.data.last_name}, ${props.manuscriptAuthor.data.author?.data.first_name}`;
});

const removable = computed(() => {
    if (props.readonly) {
        return false;
    }
    return props.manuscriptAuthor.can?.delete;
});

const emit = defineEmits([
    'delete:manuscriptAuthor',
    'edit:toggleCorrespondingAuthor',
]);
</script>

<style scoped></style>
