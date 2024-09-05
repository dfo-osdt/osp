<script setup lang="ts">
import { useLocaleTimeAgo } from '@/composables/useLocaleTimeAgo'

defineProps<{
  props: {
    value: string | null
  }
}>()
</script>

<template>
  <q-td :props="props">
    <template v-if="props.value">
      {{
        new Date(props.value).toLocaleString(`${$i18n.locale}-CA`, {
          // simple time stamp format
          dateStyle: 'short',
          timeStyle: 'short',
        })
      }}
      <span class="text-grey-8">(
        {{ useLocaleTimeAgo(new Date(props.value)).value }}
        )</span>
    </template>
    <template v-else>
      <span class="text-grey-8">-</span>
    </template>
  </q-td>
</template>

<style scoped></style>
