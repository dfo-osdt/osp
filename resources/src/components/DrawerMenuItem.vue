<script setup lang="ts">
export interface MenuItem {
  icon: string
  label: string
  to: string
  visible: boolean
  tooltipVisible: boolean
}

defineProps<{
  item: MenuItem
}>()
</script>

<template>
  <q-item
    :key="item.label"
    v-ripple
    clickable
    :to="item.to"
    :aria-label="item.label"
  >
    <q-item-section avatar>
      <q-icon :name="item.icon" />
      <q-tooltip
        v-if="item.tooltipVisible"
        anchor="center left"
        self="center end"
        class="text-body2"
      >
        {{ item.label }}
      </q-tooltip>
    </q-item-section>
    <q-item-section class="wrap-content">
      {{ item.label }}
    </q-item-section>
  </q-item>
</template>

<style scoped>
.wrap-content {
    max-width: 255px;
    white-space: normal;
}
</style>
