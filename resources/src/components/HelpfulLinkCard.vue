<script setup lang="ts">
defineProps<{
  title: string
  description: string
  url?: string
}>()
</script>

<template>
  <q-card
    v-ripple="!!url"
    flat
    bordered
    class="helpful-link-card"
    :class="{ 'cursor-pointer': url }"
    :tag="url ? 'a' : 'div'"
    :href="url"
    target="_blank"
    rel="noopener noreferrer"
  >
    <q-card-section class="q-pb-sm">
      <div v-if="url" class="absolute-right q-pa-sm">
        <div class="arrow-btn">
          <q-icon name="mdi-arrow-right" color="primary" size="xs" />
        </div>
      </div>
      <div
        class="text-h6 text-weight-medium text-primary"
        :class="{ 'q-pr-xl': url }"
      >
        {{ title }}
      </div>
    </q-card-section>
    <q-card-section class="q-pt-none">
      <div class="text-body2 text-grey-8">
        {{ description }}
      </div>
    </q-card-section>
  </q-card>
</template>

<style scoped>
.helpful-link-card {
  height: 100%;
  display: flex;
  flex-direction: column;
  text-decoration: none;
  color: inherit;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.helpful-link-card:hover {
  background-color: color-mix(in srgb, var(--q-primary) 8%, white);
}

.arrow-btn {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 1px solid currentColor;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--q-primary);
}
</style>
