<template>
    <q-select
        v-model="model"
        :options="options"
        :option-label="label"
        :readonly="readonly"
        :autocomplete="label"
        option-value="id"
        :label="$t('common.dfo-region')"
        :loading="regionStore.loading"
        emit-value
        map-options
    />
</template>

<script setup lang="ts">
// setup required stores
// use store helper
const localeStore = useLocaleStore();
const regionStore = useRegionStore();
regionStore.getRegions();

withDefaults(
    defineProps<{
        readonly?: boolean;
    }>(),
    {
        readonly: false,
    },
);

const model = defineModel<number | null>();

const label = computed(() =>
    localeStore.locale === 'fr' ? 'name_fr' : 'name_en',
);
const options = computed(
    () =>
        regionStore.regions?.sort((a, b) =>
            a[label.value].localeCompare(b[label.value]),
        ) ?? [],
);
</script>
