<template>
    <q-input
        v-model="value"
        outlined
        label="ORCID"
        class="q-ma-md"
        mask="####-####-####-###X"
        :rules="rules"
    ></q-input>
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: string;
}>();

const rules = [
    (val: string) =>
        val.length === 0 ||
        /^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/.test(val) ||
        'ORCID is invalid',
];

const value = useVModel(props, 'modelValue');
</script>

<style scoped></style>
