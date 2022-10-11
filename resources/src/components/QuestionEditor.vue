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
            ref="editor"
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
            @paste="onPaste"
        ></q-editor>
    </div>
</template>

<script setup lang="ts">
import { QEditor } from 'quasar';
import RequiredSpan from './RequiredSpan.vue';
import DOMPurify from 'dompurify';

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

const editor = ref<QEditor | null>(null);

const onPaste = (e: ClipboardEvent) => {
    if (editor === null) return;
    e.preventDefault();
    e.stopPropagation();

    // make sure clipboard has data
    if (!e.clipboardData) return;
    let text = e.clipboardData.getData('text/html');
    if (text === '') text = e.clipboardData.getData('text/plain');
    // clean all ms formatting except for allowed tags and attributes
    const cleanText = DOMPurify.sanitize(text, {
        ALLOWED_TAGS: [
            'b',
            'i',
            'u',
            'sup',
            'sub',
            'a',
            'code',
            'ul',
            'ol',
            'li',
            'blockquote',
        ],
        ALLOWED_ATTR: ['href'],
    });
    editor.value?.runCmd('insertHTML', cleanText);
};
</script>

<style scoped></style>
