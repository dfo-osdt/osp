<template>
    <q-input
        v-model="date"
        hint="ISO Date Format (YYYY-MM-DD)"
        outlined
        :mask="isoDateMask"
        :rules="rules"
    >
        <template #append>
            <q-icon name="mdi-calendar" class="cursor-pointer">
                <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                >
                    <q-date v-model="date" mask="YYYY-MM-DD">
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
    modelValue: string | null;
    required?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);

const date = useVModel(props, 'modelValue', emit);

// ISO date mask
const isoDateMask = '####-##-##';

const rules = [
    // required
    (val: string | null) => {
        if (!props.required) return true;
        const msg = 'Date is required';
        if (val === null) return msg;
        return val.length > 0 || msg;
    },
    // date actually existed
    (val: string | null) => {
        if (val === '') return true;
        if (val === null) return true;
        const date = new Date(val);
        return !isNaN(date.getTime()) || 'Invalid date';
    },
];
</script>

<style scoped></style>
