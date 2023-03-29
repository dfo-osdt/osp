<template>
    <q-input v-model="search" dense rounded outlined clearable>
        <template #prepend> <q-icon name="mdi-magnify" /> </template
    ></q-input>
</template>

<script setup lang="ts">
const props = defineProps<{
    modelValue: string | null;
}>();
const emit = defineEmits(['update:modelValue']);
const search = useVModel(props, 'modelValue', emit);
</script>

<style scoped></style>
