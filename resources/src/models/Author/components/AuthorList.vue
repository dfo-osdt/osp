<script setup lang="ts">
import type { AuthorResource } from '../Author'
import AuthorListItem from './AuthorListItem.vue'

const props = defineProps<{
  authors: AuthorResource[]
}>()
</script>

<template>
  <q-list separator role="list">
    <AuthorListItem
      v-for="author in authors"
      :key="author.data.id"
      :author="author"
    />
  </q-list>
</template>

<style scoped>

</style>
