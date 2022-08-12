<template>
    <q-page>
        <q-toolbar class="bg-teal-1">
            <q-icon name="mdi-view-dashboard" color="primary" size="sm"/>
            <q-toolbar-title>
                Dashboard
            </q-toolbar-title>
        </q-toolbar>
        <section class="q-pa-md">
            <transition
                appear
                enter-active-class="animated fadeIn"
                leave-active-class="animated fadeOut"
            >
            <q-card>test</q-card>
            </transition>
        </section>
    </q-page>
</template>

<script setup lang="ts"></script>

<style scoped></style>
