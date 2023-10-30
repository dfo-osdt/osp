<template>
    <q-chip color="light-green-3" class="q-pa-md">{{
        modelValue.data[`name_${localeStore.locale}`]
    }}</q-chip>
</template>

<script setup lang="ts">
import { ExpertiseResource } from '../Expertise';

defineProps<{
    modelValue: ExpertiseResource;
}>();

const localeStore = useLocaleStore();
</script>

<style scoped></style>
