<script setup lang="ts">
import type { AuthorEmploymentResource } from '../AuthorEmployement'
import AuthorEmploymentListItem from './AuthorEmploymentListItem.vue'

defineProps<{
  authorEmployments: AuthorEmploymentResource[]
  disabled?: boolean
}>()

const emit = defineEmits<{
  changed: []
}>()
</script>

<template>
  <q-list v-if="authorEmployments.length !== 0" bordered separator>
    <AuthorEmploymentListItem
      v-for="authorEmployment in authorEmployments"
      :key="authorEmployment.data.id"
      :author-employment="authorEmployment"
      :disabled="disabled"
      @changed="emit('changed')"
    />
  </q-list>
  <div v-else>
    <q-list bordered>
      <q-item>
        <q-item-section avatar>
          <q-icon color="primary" name="mdi-briefcase" />
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <span class="text-grey-8">{{
              $t('author-employment-list.no-employment-given')
            }}</span>
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<style scoped>

</style>
