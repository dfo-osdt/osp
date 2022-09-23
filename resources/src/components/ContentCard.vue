<template>
    <q-card outlined flat bordered>
        <q-card-section class="bg-teal-1 text-h6 text-accent"
            ><slot name="title">Title here</slot>
        </q-card-section>
        <slot name="nav"></slot>
        <q-card-section><slot></slot></q-card-section>
    </q-card>
</template>

<script setup lang="ts"></script>

<style scoped></style>
