<script setup lang="ts">
import type { ValidationRule } from 'quasar'
import { QEditor } from 'quasar'
import { cleanPastedTitle, titleToPlainText } from '@/utils/title'

const props = withDefaults(
  defineProps<{
    modelValue: string
    label?: string
    placeholder?: string
    disable?: boolean
    readonly?: boolean
    rules?: ValidationRule[]
  }>(),
  {
    label: undefined,
    placeholder: undefined,
    disable: false,
    readonly: false,
    rules: () => [],
  },
)

const value = useVModel(props, 'modelValue')

const editor = ref<QEditor | null>(null)

/** Only formats a title can carry; bold, underline and new lines are blocked. */
function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Enter') {
    e.preventDefault()
    return
  }
  if ((e.ctrlKey || e.metaKey) && ['b', 'u'].includes(e.key.toLowerCase()))
    e.preventDefault()
}

function onPaste(e: ClipboardEvent) {
  e.preventDefault()
  e.stopPropagation()
  if (!e.clipboardData)
    return
  const html = e.clipboardData.getData('text/html')
  if (html !== '') {
    editor.value?.runCmd('insertHTML', cleanPastedTitle(html))
    return
  }
  // plain text is inserted as typed: "<i>" stays literal
  editor.value?.runCmd('insertText', e.clipboardData.getData('text/plain').replace(/\s+/g, ' '))
}

// an editor left with only markup (e.g. "<br>") counts as empty for the required rules
watch(value, (newValue) => {
  if (newValue !== '' && titleToPlainText(newValue).trim() === '')
    value.value = ''
})
</script>

<template>
  <q-field
    :model-value="value"
    :label="label"
    :disable="disable"
    :readonly="readonly"
    :rules="rules"
    stack-label
    outlined
  >
    <template #control>
      <QEditor
        ref="editor"
        v-model="value"
        class="full-width"
        flat
        dense
        min-height="1.5rem"
        :placeholder="placeholder"
        :disable="disable"
        :readonly="readonly"
        :toolbar="readonly ? [] : [['italic', 'superscript', 'subscript', 'removeFormat']]"
        @keydown="onKeydown"
        @paste="onPaste"
        @drop.prevent
      />
    </template>
  </q-field>
</template>
