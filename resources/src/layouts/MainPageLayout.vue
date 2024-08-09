<script setup lang="ts">
defineProps<{
  icon?: string
  title: string
}>()

// Is the toolbar slot empty?
const slots = useSlots()
const hasToolbar = slots.toolbar !== undefined
// If the toolbar slot is not empty, then we need to add a spacer to the toolbar
const topPadding = computed(() => (hasToolbar ? '80px' : '60px'))
</script>

<template>
  <q-page :style="`padding-top: ${topPadding}`" class="bg-grey-1">
    <slot />
    <q-page-sticky expand position="top">
      <q-toolbar class="bg-teal-1">
        <q-icon v-if="icon" :name="icon" color="primary" size="sm" />
        <q-toolbar-title>{{ title }}</q-toolbar-title>
      </q-toolbar>
      <div class="col-12 bg-grey-1">
        <slot name="toolbar" />
      </div>
    </q-page-sticky>
  </q-page>
</template>

<style scoped></style>
