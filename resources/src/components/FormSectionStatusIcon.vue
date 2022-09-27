<template>
    <q-icon :name="icon" :color="color" size="sm">
        <q-tooltip>{{ tooltip }}</q-tooltip>
    </q-icon>
</template>

<script setup lang="ts">
export type FormSectionStatus = 'complete' | 'incomplete' | 'error';

const props = defineProps<{
    status: FormSectionStatus;
}>();

const icon = computed(() => {
    switch (props.status) {
        case 'complete':
            return 'mdi-checkbox-marked-circle-outline';
        case 'incomplete':
            return 'mdi-checkbox-blank-circle-outline';
        case 'error':
            return 'mdi-alert-circle-outline';
    }
});

const color = computed(() => {
    switch (props.status) {
        case 'complete':
            return 'primary';
        case 'incomplete':
            return 'primary';
        case 'error':
            return 'red';
    }
});

const tooltip = computed(() => {
    switch (props.status) {
        case 'complete':
            return 'Section complete';
        case 'incomplete':
            return 'Section incomplete';
        case 'error':
            return 'Section error';
    }
});
</script>

<style scoped></style>
