<script setup lang="ts">
export interface MenuItem {
  icon: string
  label: string
  to: string
  visible: boolean
  tooltipVisible: boolean
  external?: boolean
  href?: string
  /** show a red dot on the icon to draw attention to the item */
  dot?: boolean
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
    :to="item.external ? undefined : item.to"
    :href="item.external ? item.href : undefined"
    :target="item.external ? '_blank' : undefined"
    :rel="item.external ? 'noopener noreferrer' : undefined"
    :aria-label="item.label"
  >
    <q-item-section avatar>
      <q-icon :name="item.icon">
        <q-badge
          v-if="item.dot"
          rounded
          floating
          color="red"
        />
      </q-icon>
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
