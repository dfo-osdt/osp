<script setup lang="ts">
import { sanitizeTitle } from '@/utils/title'

const props = defineProps<{
  title: string | null | undefined
}>()

const sanitizedTitle = computed(() => sanitizeTitle(props.title))
</script>

<template>
  <!-- eslint-disable-next-line vue/no-v-html -->
  <span v-html="sanitizedTitle" />
</template>
