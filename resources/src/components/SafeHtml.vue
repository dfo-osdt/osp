<script setup lang="ts">
import DOMPurify from 'dompurify'

const props = defineProps<{
  html: string | null | undefined
}>()

const sanitizedHtml = computed(() => {
  if (!props.html) {
    return ''
  }
  return DOMPurify.sanitize(props.html)
})
</script>

<template>
  <!-- eslint-disable-next-line vue/no-v-html -->
  <div v-html="sanitizedHtml" />
</template>
