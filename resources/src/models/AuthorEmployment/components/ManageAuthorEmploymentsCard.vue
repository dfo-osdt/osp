<script setup lang="ts">
import { type AuthorEmploymentResource, AuthorEmploymentService } from '../AuthorEmployement'
import AddAuthorEmploymentDialog from './AddAuthorEmploymentDialog.vue'
import AuthorEmploymentList from './AuthorEmploymentList.vue'

const props = defineProps < {
  authorId: number
  disabled?: boolean
}>()

const loading = ref(true)
const authorEmployments = ref < AuthorEmploymentResource[] > ([])
const authorEmploymentService = new AuthorEmploymentService(props.authorId)
const showCreateDialog = ref(false)

onMounted(async () => {
  await loadAuthorEmployments()
})

async function loadAuthorEmployments() {
  if (showCreateDialog.value) {
    showCreateDialog.value = false
  }
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
        outline
        :disable="disabled"
        icon-right="mdi-briefcase-plus-outline"
        color="primary"
        label="Add Employment"
        @click="showCreateDialog = true"
      >
        <q-tooltip v-if="disabled">
          Link your ORCID to add Employment
        </q-tooltip>
        <q-tooltip v-else>
          Add Employment
        </q-tooltip>
      </q-btn>
    </div>
    <div class="q-mt-md">
      <AuthorEmploymentList :author-employments="authorEmployments" @changed="loadAuthorEmployments" />
    </div>
    <AddAuthorEmploymentDialog
      v-if="showCreateDialog"
      v-model="showCreateDialog"
      :author-id="props.authorId"
      @changed="loadAuthorEmployments"
    />
  </q-card>
</template>

<style scoped>

</style>
