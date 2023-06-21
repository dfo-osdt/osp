<template>
    <q-option-group v-model="modelValue" :options="options" inline />
</template>

<script setup lang="ts">
const { t } = useI18n();

const modelValue = defineModel<boolean>();

const options = computed(() => [
    {
        label: t("common.yes"),
        value: true,
    },
    {
        label: t("common.no"),
        value: false,
    },
]);
</script>
