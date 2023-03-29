<template>
    <q-badge outline :color="color">
        <ManuscriptStatusSpan :status="status" />
    </q-badge>
</template>

<script setup lang="ts">
import { ManuscriptRecordStatus } from '../ManuscriptRecord';
import ManuscriptStatusSpan from './ManuscriptStatusSpan.vue';

const props = defineProps<{
    status: ManuscriptRecordStatus;
}>();

// computed color of badge based on status
const color = computed(() => {
    switch (props.status) {
        case 'draft':
            return 'blue';
        case 'in_review':
            return 'orange';
        case 'reviewed':
            return 'orange';
        case 'submitted':
            return 'orange';
        case 'accepted':
            return 'green';
        case 'withdrawn':
            return 'red';
        case 'withheld':
            return 'red';
    }
});
</script>

<style scoped></style>
