<template>
    <q-input
        ref="input"
        v-model="value"
        outlined
        label="ORCID"
        mask="####-####-####-###X"
        :rules="rules"
    ></q-input>
</template>

<script setup lang="ts">
import { QInput } from 'quasar';

const { t } = useI18n();

const props = defineProps<{
    modelValue: string;
}>();

const rules = [
    (val: string) =>
        val.length === 0 ||
        /^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/.test(val) ||
        t('common.validation.orcid-invalid'),
    // check orcid checksum
    (val: string) => {
        if (val.length < 19) {
            return true;
        }
        // checksum is the last digit
        const givenCheckSum = val[18];
        const digits = val
            .substring(0, 18)
            .replaceAll('-', '')
            .split('')
            .map((x) => parseInt(x));
        const total = digits.reduce((acc, x) => {
            return (acc + x) * 2;
        }, 0);
        const remainder = total % 11;
        const result = (12 - remainder) % 11;
        const checksum = result == 10 ? 'X' : result.toString();
        return (
            givenCheckSum === checksum ||
            t('common.validation.orcid-is-invalid-checksum')
        );
    },
];

const value = useVModel(props, 'modelValue');
</script>

<style scoped></style>
