<template>
    <q-select
        v-model="value"
        :options="options"
        label="Manuscript Type"
        emit-value
        map-options
    />
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: string;
}>();

const options = ref([
    {
        label: 'Primary Manuscript',
        value: 'primary',
    },
    {
        label: 'Secondary Manuscript',
        value: 'secondary',
    },
]);

const value = useVModel(props, 'modelValue');
</script>

<style scoped></style>
