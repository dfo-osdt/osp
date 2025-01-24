<script setup lang="ts">
import AuthenticationHistoryCard from '../components/AuthenticationHistoryCard.vue'
import ChangePasswordCard from '../components/ChangePasswordCard.vue'

const authStore = useAuthStore()
</script>

<template>
  <ChangePasswordCard v-if="!authStore.openAuthOnly" class="q-mb-lg" />
  <AuthenticationHistoryCard />
</template>

<style scoped></style>
