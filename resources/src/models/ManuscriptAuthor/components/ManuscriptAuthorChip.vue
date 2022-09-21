<template>
    <q-chip
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
        <q-tooltip class="text-caption">
            <q-list dense>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar icon="mdi-bank" />
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
                        <q-avatar icon="mdi-email" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>
                            {{ manuscriptAuthor.data.author?.data.email }}
                        </q-item-label>
                    </q-item-section>
                </q-item>
            </q-list>
        </q-tooltip>
    </q-chip>
</template>

<script setup lang="ts">
import { ManuscriptAuthorResource } from '../ManuscriptAuthor';

const props = defineProps<{
    manuscriptAuthor: ManuscriptAuthorResource;
}>();

const name = computed(() => {
    return `${props.manuscriptAuthor.data.author?.data.last_name}, ${props.manuscriptAuthor.data.author?.data.first_name}`;
});

const removable = computed(() => {
    return props.manuscriptAuthor.can?.delete;
});

const emit = defineEmits(['delete:manuscriptAuthor', 'edit:manuscriptAuthor']);
</script>

<style scoped></style>
