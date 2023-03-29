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
const { t } = useI18n();

const props = defineProps<{
    modelValue: string | null;
    required?: boolean;
}>();
const emit = defineEmits(['update:modelValue']);

const doi = useVModel(props, 'modelValue', emit);

const doiInput = ref<QInput | null>(null);
const isReadOnly = computed(() => doiInput.value?.$props.readonly ?? false);

const rules = [
    // required
    (val: string | null) => {
        if (!props.required) return true;
        const msg = t('common.required');
        if (val === null) return msg;
        return val.length > 0 || msg;
    },
    (val: string | null) => {
        if (val === '') return true;
        if (val === null) return true;
        const doiRegex = /^10\.\d{4,9}\/[-._;()/:A-Z0-9]+$/i;
        return doiRegex.test(val) || t('common.validation.invalid-doi');
    },
];
</script>

<style scoped></style>
