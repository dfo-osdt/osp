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
                    <q-date v-model="date" mask="YYYY-MM-DD" :options="dateRangeFn">
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
    minDate?: string | null;
    maxDate?: string | null;
}>();

const emit = defineEmits(['update:modelValue']);

const date = useVModel(props, 'modelValue', emit);

// ISO date mask
const isoDateMask = '####-##-##';

// compute the options function to pass to the date picker
const dateRangeFn = computed(() => {
    return (val: string) : boolean => {
        const date = new Date(val);
        if(props.minDate && props.maxDate){
            return date >= new Date(props.minDate) && date <= new Date(props.maxDate)
        }
        if(props.minDate){
            return date >= new Date(props.minDate)
        }
        if(props.maxDate){
            // add a day to the max date to allow the user to select the last day
            let maxDate = new Date(props.maxDate);
            maxDate.setDate(maxDate.getDate() + 1);
            return date <= maxDate;
        }
        return true;
    }
});


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
    // date is greater or equal to min date if provided
    (val: string | null) => {
        if (val === '') return true;
        if (val === null) return true;
        if (props.minDate === undefined) return true;
        if (props.minDate === null) return true;
        const date = new Date(val);
        const minDate = new Date(props.minDate);
        return date >= minDate || 'Date is too early';
    },
    // date is less or equal to max date if provided
    (val: string | null) => {
        if (val === '') return true;
        if (val === null) return true;
        if (props.maxDate === undefined) return true;
        if (props.maxDate === null) return true;
        const date = new Date(val);
        const maxDate = new Date(props.maxDate);
        return date <= maxDate || 'Date is too late';
    },
];
</script>

<style scoped></style>
