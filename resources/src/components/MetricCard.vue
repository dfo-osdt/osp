<template>
    <q-card flat bordered class="bg-grey-1">
        <q-card-section class="q-pb-sm">
            <div v-if="to" class="absolute-right q-pa-sm">
                <q-btn
                    round
                    outline
                    size="sm"
                    icon="mdi-arrow-right"
                    color="primary"
                    :to="to"
                />
            </div>
            <div class="text-body1 text-weight-medium text-accent">
                {{ title }}
            </div>
        </q-card-section>
        <q-card-section class="q-py-none">
            <div class="text-h3 text-weight-medium">{{ value }}</div>
        </q-card-section>
        <q-card-section class="q-pt-none">
            <div class="text-body1 text-grey-8">{{ subtitle }}</div>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
// props
defineProps<{
    title: string;
    value: number;
    subtitle: string;
    to?: object | string;
}>();
</script>

<style scoped></style>
