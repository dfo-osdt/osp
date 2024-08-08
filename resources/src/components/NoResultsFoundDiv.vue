<script setup lang="ts"></script>

<template>
  <div class="flex row justify-center">
    <div class="col-12 col-lg-10 flex column text-center">
      <div class="q-ma-md">
        <q-img
          src="/assets/splash_images/undraw_void_-3-ggu.svg"
          width="150px"
        />
      </div>
      <div class="text-h5 text-weight-light text-primary q-mb-sm">
        {{ $t('common.no-results-found') }}
      </div>
      <div class="text-body1 text-grey-8">
        <p>
          {{ $t('common.no-results-found-hint') }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
