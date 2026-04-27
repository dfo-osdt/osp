<script setup lang="ts">
const props = defineProps<{
  title: string
  description: string
  url?: string
}>()

function openUrl() {
  window.open(props.url, '_blank', 'noopener,noreferrer')
}
</script>

<template>
  <q-card
    v-ripple="!!url"
    flat
    bordered
    class="helpful-link-card"
    :class="{ 'cursor-pointer': url }"
    @click="url && openUrl()"
  >
    <q-card-section class="q-pb-sm">
      <div v-if="url" class="absolute-right q-pa-sm">
        <q-btn
          round
          outline
          size="sm"
          icon="mdi-arrow-right"
          color="primary"
          :href="url"
          target="_blank"
          rel="noopener noreferrer"
          :aria-label="title"
          @click.stop
        />
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
}
</style>
