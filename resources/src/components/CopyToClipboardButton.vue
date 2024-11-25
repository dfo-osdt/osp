<script setup lang="ts">
defineProps<{
  text: string | null
}>()

const { copy, copied, isSupported } = useClipboard()
</script>

<template>
  <q-btn
    flat
    dense
    icon="mdi-content-copy"
    :disable="!isSupported || text === null"
    @click="text && copy(text)"
  >
    <q-tooltip>
      {{ copied ? $t('common.copied') : $t('common.copy') }}
    </q-tooltip>
  </q-btn>
</template>

<style scoped>

</style>
