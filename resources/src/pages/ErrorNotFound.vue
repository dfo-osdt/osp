<script setup lang="ts">
const authStore = useAuthStore()
</script>

<template>
  <div class="fullscreen flex flex-center full-height column">
    <div class="q-mb-md">
      <q-img
        src="/assets/splash_images/undraw_startled_-8-p0r.svg"
        width="450px"
      />
    </div>
    <div class="text-h1 text-primary text-weight-light">
      404
    </div>
    <div class="text-subtitle1 text-primary">
      {{ $t('common.page-not-found') }}
    </div>
    <div class="q-mt-lg">
      <q-btn
        round
        outline
        color="primary"
        :to="authStore.isAuthenticated ? '/dashboard' : '/'"
      >
        <q-icon name="mdi-home" />
      </q-btn>
    </div>
  </div>
</template>

<style scoped></style>
