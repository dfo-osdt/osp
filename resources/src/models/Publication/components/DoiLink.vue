<script setup lang="ts">
const props = defineProps<{
  doi: string | null
}>()

const url = computed(() => `https://doi.org/${props.doi}`)
</script>

<template>
  <a v-if="doi" :href="url" target="_blank">{{ doi }}</a>
  <span v-else> - </span>
</template>

<style scoped></style>
