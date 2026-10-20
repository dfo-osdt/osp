<script setup lang="ts">
import type { RegionResource } from '@/models/Region/Region'
import type { SectorResource } from '@/models/Sector/Sector'

/**
 * Menu items for the sector, region and emeritus status recorded on a
 * manuscript or publication author. They are only set on DFO rows, so
 * nothing is shown for other affiliations.
 */
const props = defineProps<{
  sector?: SectorResource
  region?: RegionResource
  isEmeritus: boolean | null
}>()

const localeStore = useLocaleStore()

function localizedName(item?: { data: { name_en: string, name_fr: string } }): string | null {
  if (!item)
    return null
  return localeStore.locale === 'en' ? item.data.name_en : item.data.name_fr
}

const sectorName = computed(() => localizedName(props.sector))
const regionName = computed(() => localizedName(props.region))
</script>

<template>
  <q-item v-if="sectorName" data-test="authorship-sector">
    <q-item-section avatar>
      <q-avatar icon="mdi-sitemap-outline" text-color="primary" />
    </q-item-section>
    <q-item-section>
      <q-item-label>{{ sectorName }}</q-item-label>
      <q-item-label caption>
        {{ $t('common.dfo-sector') }}
      </q-item-label>
    </q-item-section>
  </q-item>
  <q-item v-if="regionName" data-test="authorship-region">
    <q-item-section avatar>
      <q-avatar icon="mdi-map-marker-outline" text-color="primary" />
    </q-item-section>
    <q-item-section>
      <q-item-label>{{ regionName }}</q-item-label>
      <q-item-label caption>
        {{ $t('common.dfo-region') }}
      </q-item-label>
    </q-item-section>
  </q-item>
  <q-item v-if="isEmeritus" data-test="authorship-emeritus">
    <q-item-section avatar>
      <q-avatar icon="mdi-school-outline" text-color="primary" />
    </q-item-section>
    <q-item-section>
      <q-item-label>{{ $t('author.emeritus') }}</q-item-label>
    </q-item-section>
  </q-item>
</template>
