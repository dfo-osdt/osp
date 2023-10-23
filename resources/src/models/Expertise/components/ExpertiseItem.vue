<template>
    <q-item>
        <q-item-section>
            <q-item-label :class="topLevel ? 'text-bold' : ''">{{
                modelValue.data[`name_${localeStore.locale}`]
            }}</q-item-label>
            <q-item-label v-if="modelValue.data.taxonomy_path_en" caption>
                {{ modelValue.data[`taxonomy_path_${localeStore.locale}`] }}
            </q-item-label>
            <q-item-label v-if="topLevel" caption>
                <span class="text-italic">{{
                    $t('expertise.top-level-expertise')
                }}</span>
            </q-item-label>
        </q-item-section>
    </q-item>
</template>

<script setup lang="ts">
import { ExpertiseResource } from '../Expertise';

const props = defineProps<{
    modelValue: ExpertiseResource;
}>();

const localeStore = useLocaleStore();

const topLevel = computed(() => props.modelValue.data.parent_tid === null);
</script>

<style scoped></style>
