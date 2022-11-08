<template>
    <q-input
        ref="doiInput"
        v-model="doi"
        label="DOI"
        outlined
        :rules="rules"
        hint="Format: 10.1038/xyz123"
        :hide-hint="isReadOnly"
    />
</template>

<script setup lang="ts">
import { QInput } from 'quasar';

const props = defineProps<{
    modelValue: string;
}>();
const emit = defineEmits(['update:modelValue']);

const doi = useVModel(props, 'modelValue', emit);

const doiInput = ref<QInput | null>(null);
const isReadOnly = computed(() => doiInput.value?.$props.readonly ?? false);

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
