<template>
    <q-select
        v-model="value"
        :options="options"
        label="$t('common.manuscript-type')"
        emit-value
        map-options
    />
</template>

<script setup lang="ts">
const { t } = useI18n();

const props = defineProps<{
    modelValue: string;
}>();

const options = ref([
    {
        label: t('common.primary-manuscript'),
        value: 'primary',
    },
    {
        label: t('common.secondary-manuscript'),
        value: 'secondary',
    },
]);

const value = useVModel(props, 'modelValue');
</script>

<style scoped></style>
