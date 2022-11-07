<template>
    <q-input v-model="doi" label="DOI" outlined :rules="rules" />
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: string;
}>();
const emit = defineEmits(['update:modelValue']);

const doi = useVModel(props, 'modelValue', emit);

const rules = [
    (val: string) => {
        if (val === '') {
            return true;
        }
        const doiRegex = /^10\.\d{4,9}\/[-._;()/:A-Z0-9]+$/i;
        return doiRegex.test(val) || 'Invalid DOI';
    },
    // required
    (val: string) => val.length > 0 || 'DOI is required',
];
</script>

<style scoped></style>
