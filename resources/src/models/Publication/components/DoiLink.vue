<script setup lang="ts">
const props = defineProps<{
  doi: string | null
}>()
</script>

<template>
  <a v-if="doi" :href="doi" target="_blank">{{ doi }}</a>
  <span v-else> - </span>
</template>

<style scoped></style>
