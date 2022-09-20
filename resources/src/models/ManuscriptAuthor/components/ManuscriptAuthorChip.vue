<template>
    <q-chip
        :removable="removable"
        clickable
        color="teal-2"
        @remove="emit('delete:manuscriptAuthor')"
        @click="emit('edit:manuscriptAuthor')"
    >
        {{ name }}
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
