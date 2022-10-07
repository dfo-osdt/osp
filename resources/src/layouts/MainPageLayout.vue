<template>
    <q-page style="padding-top: 60px" class="bg-grey-1">
        <slot></slot>
        <q-page-sticky expand position="top">
            <q-toolbar class="bg-teal-1">
                <q-icon v-if="icon" :name="icon" color="primary" size="sm" />
                <q-toolbar-title>{{ title }}</q-toolbar-title>
            </q-toolbar>
        </q-page-sticky>
    </q-page>
</template>

<script setup lang="ts">
defineProps<{
    icon?: string;
    title: string;
}>();
</script>

<style scoped></style>
