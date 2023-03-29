<template>
    <q-badge outline color="blue">
        <PublicationStatusSpan :status="status" />
    </q-badge>
</template>

<script setup lang="ts">
import { PublicationStatus } from '../Publication';
import PublicationStatusSpan from './PublicationStatusSpan.vue';

defineProps<{
    status: PublicationStatus;
}>();
</script>

<style scoped></style>
