<template>
    <q-select
        v-model="value"
        :options="options"
        :option-label="label"
        autocomplete="name_en"
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

const props = defineProps<{
    modelValue: number | null;
}>();

const emit = defineEmits(['update:modelValue']);

const value = useVModel(props, 'modelValue', emit);

const label = computed(() =>
    localeStore.locale === 'fr' ? 'name_fr' : 'name_en'
);
const options = computed(
    () =>
        regionStore.regions?.sort((a, b) =>
            a[label.value].localeCompare(b[label.value])
        ) ?? []
);
</script>
