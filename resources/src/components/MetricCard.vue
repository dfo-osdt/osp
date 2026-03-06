<script setup lang="ts">
import { computed, toRef } from 'vue'
import { useCountUp } from '@/composables/useCountUp'

const props = defineProps<{
  title: string
  value: number
  subtitle: string
  suffix?: string
  animate?: boolean
  to?: object | string
}>()

const animatedValue = useCountUp(toRef(props, 'value'))

const displayValue = computed(() => {
  const val = props.animate ? animatedValue.value : props.value
  if (val === 0) {
    return '0'
  }
  return val.toLocaleString()
})
</script>

<template>
  <q-card flat bordered>
    <q-card-section class="q-pb-sm">
      <div v-if="to" class="absolute-right q-pa-sm">
        <q-btn
          round
          outline
          size="sm"
          icon="mdi-arrow-right"
          color="primary"
          :to="to"
          :aria-label="title"
        />
      </div>
      <div class="text-body1 text-weight-medium text-primary">
        {{ title }}
      </div>
    </q-card-section>
    <q-card-section class="q-py-none">
      <div class="text-h3 text-weight-medium">
        {{ displayValue }}<span v-if="suffix" class="metric-suffix text-grey-7">{{ suffix }}</span>
      </div>
    </q-card-section>
    <q-card-section class="q-pt-none">
      <div class="text-body1 text-grey-8">
        {{ subtitle }}
      </div>
    </q-card-section>
  </q-card>
</template>

<style scoped>
.metric-suffix {
    font-size: 0.6em;
    vertical-align: middle;
    padding-left: 0.15em;
}
</style>
