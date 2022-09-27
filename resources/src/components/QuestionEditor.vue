<template>
    <div>
        <div class="q-ml-xs">
            <div class="text-body1 text-primary text-weight-medium">
                {{ title }}<required-span v-if="required" />
            </div>
            <div class="q-my-xs">
                <slot></slot>
            </div>
        </div>
        <q-editor
            v-model="value"
            :disable="disable"
            :readonly="readonly"
            :toolbar="
                readonly
                    ? []
                    : [
                          ['bold', 'italic', 'underline'],
                          ['superscript', 'subscript', 'quote'],
                          ['link', 'code'],
                          ['ordered', 'unordered'],
                          ['undo', 'redo', 'removeFormat'],
                      ]
            "
            toolbar-bg="teal-1"
        ></q-editor>
    </div>
</template>

<script setup lang="ts">
import RequiredSpan from './RequiredSpan.vue';

const props = withDefaults(
    defineProps<{
        title: string;
        modelValue: string;
        disable?: boolean;
        readonly?: boolean;
        required?: boolean;
    }>(),
    {
        disable: false,
        readonly: false,
        required: false,
    }
);

const value = useVModel(props, 'modelValue');
</script>

<style scoped></style>
