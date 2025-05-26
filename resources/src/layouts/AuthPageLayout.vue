<script setup lang="ts"></script>

<template>
  <q-page class="flex q-pt-xl">
    <div class="row fit justify-center">
      <div class="col-10 col-sm-6 col-md-5 col-lg-4">
        <div class="col fit">
          <!-- logo and app name centered -->
          <div class="col-12 flex justify-center q-mb-md">
            <q-img src="/assets/logo.svg" width="150px" />
          </div>
          <div class="col-12 q-mb-xl">
            <router-view />
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<style scoped></style>
