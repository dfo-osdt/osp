<template>
    <q-layout view="hHh lpR fff">
        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-toolbar-title>
                    <q-avatar>
                        <img
                            src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg"
                        />
                    </q-avatar>
                    Title
                </q-toolbar-title>
            </q-toolbar>
        </q-header>
        <q-page-container>
            <router-view />
        </q-page-container>
        <q-footer class="bg-grey-8 text-white flex flex-center">
            <div class="q-pa-md">Footer text</div>
        </q-footer>
    </q-layout>
</template>

<script setup lang="ts"></script>

<style scoped></style>
