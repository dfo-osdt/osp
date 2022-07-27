<script setup lang="ts">
import { onMounted } from 'vue';
import { useSanctum } from './api/sanctum';

onMounted(async () => {
    const { csrf } = useSanctum();
    const test = await csrf();
    console.log(test);
});
</script>

<template>
    <router-view />
</template>

<style></style>
