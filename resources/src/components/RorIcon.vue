<script setup lang="ts">
defineProps<{
  size?: string
}>()
</script>

<template>
  <q-icon
    name="img:/assets/ror/ror-icon-rgb-transparent.svg"
    :size="size ?? '16px'"
    alt="ROR logo"
  />
</template>
