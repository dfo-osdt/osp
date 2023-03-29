<template>
    <div class="fullscreen flex flex-center full-height column">
        <div class="q-mb-md">
            <q-img
                src="/assets/splash_images/undraw_donut_love_kau1.svg"
                width="450px"
            />
        </div>
        <div class="text-h1 text-primary text-weight-light">403</div>
        <div class="text-subtitle1 text-primary">
            {{ $t('error-invalid-signature.invalid-signature') }}
        </div>
        <div class="text-body2 text-grey-8">
            {{ $t('error-invalid-signature.link-expired-text') }}
        </div>
        <div class="q-mt-lg">
            <q-btn
                round
                outline
                color="primary"
                :to="authStore.isAuthenticated ? '/dashboard' : '/'"
                icon="mdi-home"
            >
            </q-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
const authStore = useAuthStore();
</script>

<style scoped></style>
