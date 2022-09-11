<template>
    <q-list separator>
        <q-item
            v-for="manuscript in manuscripts"
            :key="manuscript.data.id"
            clickable
            :to="{
                name: 'manuscript',
                params: { id: manuscript.data.id },
            }"
        >
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-accent"
                    >{{ manuscript.data.title }}</q-item-label
                >
                <q-item-label caption> Authors here... </q-item-label>
            </q-item-section>
            <q-item-section side top>
                <q-badge outline color="blue" label="Draft" class="text-body2"
            /></q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import { ManuscriptRecordResource } from '../ManuscriptRecord';

const props = defineProps<{
    manuscripts: ManuscriptRecordResource[];
}>();
</script>

<style scoped></style>
