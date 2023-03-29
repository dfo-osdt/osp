<template>
    <MainPageLayout :title="$t('common.manuscript-record')">
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
                <q-route-tab
                    :label="$t('common.form')"
                    :to="`/manuscript/${id}/form`"
                />
                <q-route-tab
                    :label="$t('common.reviews')"
                    :to="`/manuscript/${id}/reviews`"
                />
                <q-route-tab
                    :label="$t('common.progress')"
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
