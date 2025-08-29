<script setup lang="ts">
import ManageAuthorExpertise from '../components/ManageAuthorExpertise.vue'
import ManageAuthorOrcidCard from '../components/ManageAuthorOrcidCard.vue'
import ManageAuthorProfileCard from '../components/ManageAuthorProfileCard.vue'

const authStore = useAuthStore()
</script>

<template>
  <div class="q-mb-xl">
    <ManageAuthorProfileCard
      v-if="authStore.user && !authStore.loading"
      :author-id="authStore.user.author.data.id"
      secondary
    />
    <ManageAuthorOrcidCard
      v-if="authStore.user && !authStore.loading"
    />
    <ManageAuthorExpertise
      v-if="authStore.user && !authStore.loading"
      :author-id="authStore.user.author.data.id"
    />
  </div>
</template>

<style scoped></style>
