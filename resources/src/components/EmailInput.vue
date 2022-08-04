<template>
    <q-input
        v-model="email"
        type="email"
        filled
        label="Your email"
        lazy-rules
        :rules="[
            (val) => !!val || 'Email is required',
            // must be valid email
            (val) => /^\S+@\S+\.\S+$/.test(val) || 'Email is invalid',
        ]"
    />
</template>

<script setup lang="ts"></script>

<style scoped></style>
