<template>
    <template v-if="isDefinition">
        <span
            >(<span class="text-red">*</span>) Fields marked with an * are
            required.</span
        >
    </template>
    <template v-else>
        <span class="text-red q-ml-xs"
            >*<q-tooltip>This field is required</q-tooltip></span
        >
    </template>
</template>

<script setup lang="ts">
withDefaults(
    defineProps<{
        isDefinition?: boolean;
    }>(),
    {
        isDefinition: false,
    }
);
</script>

<style scoped></style>
