<template>
    <a :href="url" target="_blank">{{ doi }}</a>
</template>

<script setup lang="ts">
const props = defineProps<{
    doi: string;
}>();

const url = computed(() => `https://doi.org/${props.doi}`);
</script>

<style scoped></style>
