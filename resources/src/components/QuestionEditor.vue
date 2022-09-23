<template>
    <div>
        <div class="q-ml-xs">
            <div class="text-body1 text-primary text-weight-medium">
                {{ title }}
            </div>
            <div class="q-my-xs">
                <slot></slot>
            </div>
        </div>
        <q-editor
            v-model="value"
            :disable="disable"
            :readonly="readonly"
            :toolbar="[
                ['bold', 'italic', 'underline'],
                ['superscript', 'subscript', 'quote'],
                ['link', 'code'],
                ['ordered', 'unordered'],
                ['undo', 'redo', 'removeFormat'],
            ]"
            toolbar-bg="teal-1"
        ></q-editor>
    </div>
</template>

<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        title: string;
        modelValue: string;
        disable?: boolean;
        readonly?: boolean;
    }>(),
    {
        disable: false,
        readonly: false,
    }
);

const value = useVModel(props, 'modelValue');

const toolbar = [
    ['bold', 'italic', 'strike', 'underline'],
    ['upload', 'save'],
];
</script>

<style scoped></style>
