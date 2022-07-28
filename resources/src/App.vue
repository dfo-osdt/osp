<script setup lang="ts">
import { useSpeechRecognition } from '@vueuse/core';
import { emitWarning } from 'process';
import { onMounted } from 'vue';
import { useSanctum } from './api/sanctum';

onMounted(async () => {
    const { login, user, logout } = useSanctum();
    // await user().catch((error) => {
    //     console.log(login('test@test.com', 'password'));
    // });
    // await new Promise((resolve) => setTimeout(resolve, 1000));
    // console.log(await user());
    console.log(login('test@test.com', 'password'));
    console.log(await user());
});
</script>

<template>
    <router-view />
</template>

<style></style>
