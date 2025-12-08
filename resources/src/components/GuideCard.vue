<script setup lang="ts">
defineProps<{
  title: string
  description: string
  actionLabel: string
  icon?: string
  color?: string
}>()

const emit = defineEmits<{
  (e: 'action'): void
}>()
</script>

<template>
  <q-card flat bordered class="guide-card">
    <q-card-section class="q-pb-sm">
      <div class="row items-center q-gutter-sm q-mb-sm">
        <q-icon
          v-if="icon"
          :name="icon"
          :color="color || 'primary'"
          size="sm"
        />
        <div
          class="text-h6 text-weight-medium"
          :class="`text-${color || 'primary'}`"
        >
          {{ title }}
        </div>
      </div>
    </q-card-section>
    <q-card-section class="q-pt-none q-pb-sm">
      <div class="text-body2 text-grey-8">
        {{ description }}
      </div>
    </q-card-section>
    <q-card-actions align="right">
      <q-btn
        outline
        :color="color || 'primary'"
        :label="actionLabel"
        @click="emit('action')"
      />
    </q-card-actions>
  </q-card>
</template>

<style scoped>
.guide-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}
</style>
