<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <div class="flex row justify-center">
    <div class="col-12 col-lg-10 flex column text-center">
      <div>
        <q-img
          src="/assets/splash_images/undraw_road_sign_re_3kc3.svg"
          width="250px"
        />
      </div>
      <div class="text-h5 text-weight-light text-primary q-mb-sm">
        {{ title }}
      </div>
      <div class="text-body1 text-grey-8 text-left">
        <p>
          {{ $t('no-manuscript-exists-div.p1') }}
        </p>

        <p>
          {{ $t('no-manuscript-exists-div.p2') }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
