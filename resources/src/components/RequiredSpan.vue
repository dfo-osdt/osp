<template>
    <template v-if="isDefinition">
        <span
            >(<span class="text-red">*</span>)&nbsp;{{
                $t('required-span.definition')
            }}</span
        >
    </template>
    <template v-else>
        <span class="text-red q-ml-xs"
            >*<q-tooltip>{{ $t('required-span.tooltip') }}</q-tooltip></span
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
