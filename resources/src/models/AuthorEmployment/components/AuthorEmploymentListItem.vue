<script setup lang="ts">
import type { AuthorEmploymentResource } from '../AuthorEmployement'

const props = defineProps<{
  authorEmployment: AuthorEmploymentResource
}>()
</script>

<template>
  <q-item>
    <q-item-section>
      <pre>{{ authorEmployment.data }}</pre>
    </q-item-section>
  </q-item>
</template>

<style scoped>

</style>
