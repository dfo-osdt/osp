<template>
    <q-input
        v-model="date"
        hint="Format: YYYY/MM/DD"
        outlined
        mask="date"
        :rules="rules"
    >
        <template #append>
            <q-icon name="mdi-calendar" class="cursor-pointer">
                <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                >
                    <q-date v-model="date">
                        <div class="row items-center justify-end">
                            <q-btn
                                v-close-popup
                                label="Close"
                                color="primary"
                                flat
                            />
                        </div>
                    </q-date>
                </q-popup-proxy>
            </q-icon>
        </template>
    </q-input>
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: string;
    required?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);

const date = useVModel(props, 'modelValue', emit);

const rules = [
    // valid format
    (val: string) => {
        if (val === '') {
            return true;
        }
        const dateRegex = /^\d{4}\/\d{2}\/\d{2}$/;
        return dateRegex.test(val) || 'Invalid Format: YYYY/MM/DD';
    },
    // date actually existed
    (val: string) => {
        if (val === '') {
            return true;
        }
        const date = new Date(val);
        return !isNaN(date.getTime()) || 'Invalid date';
    },
    // required
    (val: string) =>
        props.required ? val.length > 0 || 'Date is required' : true,
];
</script>

<style scoped></style>
