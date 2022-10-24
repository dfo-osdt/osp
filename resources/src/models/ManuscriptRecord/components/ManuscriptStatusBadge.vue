<template>
    <q-badge outline color="blue">
        <ManuscriptStatusSpan :status="status" />
    </q-badge>
</template>

<script setup lang="ts">
import { ManuscriptRecordStatus } from '../ManuscriptRecord';
import ManuscriptStatusSpan from './ManuscriptStatusSpan.vue';

defineProps<{
    status: ManuscriptRecordStatus;
}>();
</script>

<style scoped></style>
