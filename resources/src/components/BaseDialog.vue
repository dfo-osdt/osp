<script setup lang="ts">
defineProps<{
  title: string
}>()

defineEmits<{
  (event: 'close'): void
}>()
</script>

<template>
  <q-dialog>
    <q-card style="width: 700px; max-width: 80vw" outlined>
      <q-card-section class="bg-teal-1 text-h6 text-primary">
        <div class="flex justify-between">
          <div>{{ title }}</div>
          <div>
            <q-btn
              v-close-popup
              icon="close"
              flat
              round
              dense
              class="q-ml-auto"
              @click="$emit('close')"
            />
          </div>
        </div>
      </q-card-section>
      <q-separator />
      <slot />
    </q-card>
  </q-dialog>
</template>

<style scoped></style>
