<script setup lang="ts"></script>

<template>
  <div class="flex row justify-center">
    <div class="col-12 col-lg-10 flex column text-center">
      <div>
        <q-img
          src="/assets/splash_images/undraw_done_checking_re_6vyx.svg"
          width="250px"
          class="q-mb-sm"
        />
      </div>
      <div class="text-h5 text-weight-light text-primary q-mb-sm">
        {{ $t('no-management-review.title') }}
      </div>
      <div class="text-body1 text-grey-8">
        <p>
          {{ $t('no-management-review.subtitle') }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
