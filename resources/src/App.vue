<script setup lang="ts">
import { onMounted } from 'vue';
import HelloWorldVue from './components/HelloWorld.vue';
import { csrf } from './api/sanctum';

onMounted(async () => {
    const test = await csrf();
    console.log(test);
});

const log = (msg: string) => {
    console.log(msg);
};

const lala = 'Super';
</script>

<template>
    <v-app>
        <v-main>
            <v-container>
                <v-row>
                    <v-col cols="12" class="d-flex justify-center"
                        ><div class="text-h1">Open Science Portal</div></v-col
                    ></v-row
                >
            </v-container>
        </v-main>
    </v-app>
</template>

<style></style>
