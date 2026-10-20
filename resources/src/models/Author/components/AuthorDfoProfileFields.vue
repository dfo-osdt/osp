<script setup lang="ts">
import RegionSelect from '@/models/Region/components/RegionSelect.vue'
import SectorSelect from '@/models/Sector/components/SectorSelect.vue'

const props = defineProps<{
  required: boolean
}>()

const sectorId = defineModel<number | null>('sectorId', { required: true })
const regionId = defineModel<number | null>('regionId', { required: true })
const isEmeritus = defineModel<boolean>('isEmeritus', { required: true })

const { t } = useI18n()

const rules = computed(() =>
  props.required
    ? [(val: number | null) => val !== null || t('common.required')]
    : [],
)
</script>

<template>
  <div class="row q-col-gutter-md">
    <SectorSelect
      v-model="sectorId"
      class="col-12 col-md-6"
      :rules="rules"
      :clearable="!required"
    />
    <RegionSelect
      v-model="regionId"
      class="col-12 col-md-6"
      :rules="rules"
      :clearable="!required"
    />
    <div class="col-12">
      <q-toggle
        v-model="isEmeritus"
        :label="$t('author.emeritus')"
      />
      <div class="text-caption text-grey-7">
        {{ $t('author.emeritus-hint') }}
      </div>
    </div>
  </div>
</template>
