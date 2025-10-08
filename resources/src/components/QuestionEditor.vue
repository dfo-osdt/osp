<script setup lang="ts">
import DOMPurify from 'dompurify'
import { QEditor } from 'quasar'
import RequiredSpan from './RequiredSpan.vue'

const props = withDefaults(
  defineProps<{
    title: string
    modelValue: string
    disable?: boolean
    readonly?: boolean
    required?: boolean
    hideEditor?: boolean
  }>(),
  {
    disable: false,
    readonly: false,
    required: false,
    hideEditor: false,
  },
)

const value = useVModel(props, 'modelValue')

const editor = ref<QEditor | null>(null)

function onPaste(e: ClipboardEvent) {
  if (editor.value === null)
    return
  e.preventDefault()
  e.stopPropagation()

  // make sure clipboard has data
  if (!e.clipboardData)
    return
  let text = e.clipboardData.getData('text/html')
  if (text === '')
    text = e.clipboardData.getData('text/plain')
  // clean all formatting except for allowed tags and attributes
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
  })
  editor.value?.runCmd('insertHTML', cleanText)
}

watch(value, (newValue) => {
  // First check if content is only empty HTML (br tags, empty divs/paragraphs, &nbsp;)
  const div = document.createElement('div')
  div.innerHTML = newValue
  const textContent = (div.textContent || div.innerText || '').trim()

  if (textContent === '') {
    value.value = ''
    return
  }

  // Remove leading &nbsp; entities and whitespace
  const cleaned = newValue.replace(/^(&nbsp;|\s)+/, '')
  if (cleaned !== newValue) {
    value.value = cleaned
  }
})
</script>

<template>
  <div>
    <div class="q-ml-xs">
      <div class="text-body1 text-primary text-weight-medium">
        {{ title }}<RequiredSpan v-if="required" />
      </div>
      <div class="q-my-xs">
        <slot />
      </div>
    </div>
    <QEditor
      v-if="!hideEditor"
      ref="editor"
      v-model="value"
      :disable="disable"
      :readonly="readonly"
      :toolbar="
        readonly === true
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
    />
  </div>
</template>

<style scoped></style>
