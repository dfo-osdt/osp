<template>
    <q-select
        v-model="locale"
        emit-value
        map-options
        class="col-12 col-md-6"
        :label="$t('LocaleSelect.label')"
        outlined
        :options="options"
    />
</template>

<script setup lang="ts">
import { Locale } from '@/stores/LocaleStore';
const { t } = useI18n();

const props = defineProps<{
    modelValue: Locale;
}>();

const emit = defineEmits<{
    (event: 'update:modelValue', value: Locale): void;
}>();

const locale = useVModel(props, 'modelValue', emit);

const options = computed(() => [
    { label: t('common.english'), value: 'en' },
    { label: t('common.french'), value: 'fr' },
]);
</script>

<style scoped></style>
