<template>
    <q-item>
        <q-item-section>
            <q-item-label>{{
                modelValue.data[`name_${localeStore.locale}`]
            }}</q-item-label>
        </q-item-section>
    </q-item>
</template>

<script setup lang="ts">
import { ExpertiseResource } from '../Expertise';

defineProps<{
    modelValue: ExpertiseResource;
}>();

const localeStore = useLocaleStore();
</script>

<style scoped></style>
