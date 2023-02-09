<template>
    <div class="flex flex-center full-height column">
        <div class="q-mb-md q-mt-xl">
            <q-img
                src="/assets/splash_images/undraw_laravel_and_vue_-59-tp.svg"
                width="250px"
            />
        </div>
        <div class="text-h4 text-primary text-weight-light">
            {{ $t('under-construction.title') }}
        </div>
        <div class="text-subtitle1 text-primary">
            {{ $t('under-construction.subtitle') }}
        </div>
        <div class="q-mt-lg">
            <q-btn round outline color="primary" @click="back()">
                <q-icon name="mdi-step-backward" />
            </q-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
const router = useRouter();

function back() {
    router.go(-1);
}
</script>

<style scoped></style>
