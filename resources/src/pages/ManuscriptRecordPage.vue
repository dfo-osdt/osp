<template>
    <MainPageLayout title="Manuscript Record">
        <template #toolbar>
            <q-tabs
                dense
                narrow-indicator
                inline-label
                align="left"
                indicator-color="primary"
                active-color="primary"
                class="text-grey-8"
            >
                <q-route-tab label="Form" :to="`/manuscript/${id}/form`" />
                <q-route-tab
                    label="Reviews"
                    :to="`/manuscript/${id}/reviews`"
                />
                <q-route-tab
                    label="Progress"
                    :to="`/manuscript/${id}/progress`"
                />
            </q-tabs>
            <q-separator />
        </template>
        <RouterView />
    </MainPageLayout>
</template>

<script setup lang="ts">
import MainPageLayout from '@/layouts/MainPageLayout.vue';

defineProps<{
    id: number;
}>();
</script>

<style scoped></style>
