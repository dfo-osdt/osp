<script setup lang="ts">
export interface DateRange {
  from: string
  to: string
}

defineProps<{
  label?: string
  disable?: boolean
}>()

const { t } = useI18n()

const model = defineModel<DateRange | null>({ default: null })

/** QDate range model uses YYYY/MM/DD format */
interface QDateRange {
  from: string
  to: string
}

const qDateModel = computed<QDateRange | null>({
  get() {
    if (!model.value) {
      return null
    }
    return {
      from: model.value.from.replaceAll('-', '/'),
      to: model.value.to.replaceAll('-', '/'),
    }
  },
  set(val) {
    if (!val) {
      model.value = null
      return
    }
    model.value = {
      from: val.from.replaceAll('/', '-'),
      to: val.to.replaceAll('/', '-'),
    }
  },
})

const displayText = computed(() => {
  if (!model.value) {
    return ''
  }
  return `${model.value.from} — ${model.value.to}`
})

function setThisFiscalYear(): void {
  const now = new Date()
  const year = now.getMonth() >= 3 ? now.getFullYear() : now.getFullYear() - 1
  model.value = {
    from: `${year}-04-01`,
    to: `${year + 1}-03-31`,
  }
}

function setLastFiscalYear(): void {
  const now = new Date()
  const year = now.getMonth() >= 3 ? now.getFullYear() - 1 : now.getFullYear() - 2
  model.value = {
    from: `${year}-04-01`,
    to: `${year + 1}-03-31`,
  }
}

function clear(): void {
  model.value = null
}
</script>

<template>
  <q-input
    :model-value="displayText"
    :label="label"
    outlined
    readonly
    :disable="disable"
  >
    <template #append>
      <q-icon
        v-if="model"
        name="mdi-close-circle"
        class="cursor-pointer"
        @click="clear"
      />
      <q-icon name="mdi-calendar-range" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date v-model="qDateModel" range mask="YYYY/MM/DD">
            <div class="row items-center q-gutter-sm justify-between">
              <div class="q-gutter-xs">
                <q-btn
                  :label="t('publications-view.this-fiscal-year')"
                  flat
                  dense
                  size="sm"
                  color="primary"
                  @click="setThisFiscalYear"
                />
                <q-btn
                  :label="t('publications-view.last-fiscal-year')"
                  flat
                  dense
                  size="sm"
                  color="primary"
                  @click="setLastFiscalYear"
                />
              </div>
              <q-btn
                v-close-popup
                :label="t('common.close')"
                color="primary"
                flat
              />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>
