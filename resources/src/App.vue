<script setup lang="ts">
import { onMounted, ref } from 'vue';
import HelloWorldVue from './components/HelloWorld.vue';
import { csrf } from './api/sanctum';

onMounted(async () => {
    const test = await csrf();
    console.log(test);
});

let loading = ref(false);

const log = (evt: string) => {
    console.log(evt);
    loading.value = !loading.value;
};
</script>

<template>
    <p>
        <q-btn
            color="primary"
            icon="mdi-check"
            label="OK"
            :loading="loading"
            @click="log('supa')"
        />
    </p>
</template>

<style></style>
