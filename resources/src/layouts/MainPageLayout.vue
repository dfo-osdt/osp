<template>
    <q-page>
        <q-toolbar class="bg-teal-1">
            <q-icon v-if="icon" :name="icon" color="primary" size="sm" />
            <q-toolbar-title>{{title}}</q-toolbar-title>
        </q-toolbar>
        <slot></slot>
    </q-page>
</template>

<script setup lang="ts">

defineProps<{
    icon?: string
    title: string
}>();

</script>

<style scoped></style>
