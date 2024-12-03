<script setup lang="ts">
import type { AuthorResource } from '../Author'

const props = defineProps<{
  authors: AuthorResource[]
}>()
</script>

<template>
  <q-list separator role="list">
    <q-item
      v-for="author in authors"
      :key="author.data.id"
      clickable
      :to="{
        name: 'author',
        params: { id: author.data.id },
      }"
    >
      <q-item-section>
        <q-item-label
          class="text-body1 text-weight-medium text-primary"
        >
          {{ author.data.last_name }}, {{ author.data.first_name }}
        </q-item-label>
        <q-item-label caption lines="2">
          <template v-if="author.data.organization">
            <span>
              {{ author.data.organization.data.name_en }}
            </span>
          </template>
        </q-item-label>
      </q-item-section>
    </q-item>
  </q-list>
</template>

<style scoped>

</style>
