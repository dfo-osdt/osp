<template>
    <q-card outlined flat bordered>
        <q-card-section class="bg-teal-1 text-h6 text-accent">
            <div class="row flex justify-between">
                <div><slot name="title">Title here</slot></div>
                <div><slot name="title-right"></slot></div>
            </div>
        </q-card-section>
        <slot name="nav"></slot>
        <q-card-section><slot></slot></q-card-section>
    </q-card>
</template>

<script setup lang="ts"></script>

<style scoped></style>
