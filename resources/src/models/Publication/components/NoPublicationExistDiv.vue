<template>
    <div class="flex row justify-center">
        <div class="col-12 col-lg-10 flex column text-center">
            <div>
                <q-img
                    src="/assets/splash_images/undraw_scientist_0ft9.svg"
                    width="250px"
                    class="q-mb-sm"
                />
            </div>
            <div class="text-h5 text-weight-light text-primary q-mb-sm">
                {{ title }}
            </div>
            <div class="text-body1 text-grey-8 text-left">
                <p>
                    {{ $t('no-publication-exist-div.p1') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<style scoped></style>
