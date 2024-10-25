<script setup lang="ts">
import { type AuthorEmploymentResource, AuthorEmploymentService } from '../AuthorEmployement'

const props = defineProps < {
  authorId: number
}>()

const loading = ref(true)
const authorEmployments = ref < AuthorEmploymentResource[] > ([])
const authorEmploymentService = new AuthorEmploymentService(props.authorId)
const showCreateDialog = ref(false)

onMounted(async () => {
  await loadAuthorEmployments()
})

async function loadAuthorEmployments() {
  loading.value = true
  const response = await authorEmploymentService.list()
  authorEmployments.value = response.data
  loading.value = false
}
</script>

<template>
  <q-card flat>
    <div class="q-pt-sm row justify-end">
      <q-btn
        color="primary"
        label="Add Employment"
        @click="showCreateDialog = true"
      />
    </div>
    <pre>
    {{ authorEmployments }}
  </pre>
  </q-card>
</template>

<style scoped>

</style>
