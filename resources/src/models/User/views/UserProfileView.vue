<script setup lang="ts">
import ManageUserProfileCard from '../components/ManageUserProfileCard.vue'
import UserRolesCard from '../components/UserRolesCard.vue'

const authStore = useAuthStore()
</script>

<template>
  <ManageUserProfileCard
    v-if="authStore.user && !authStore.loading"
    :user-id="authStore.user.id"
    @saved="authStore.refreshUser(true)"
  />
  <UserRolesCard
    v-if="authStore.user && !authStore.loading"
    :user="authStore.user"
    class="q-mt-lg"
  />
</template>

<style scoped></style>
