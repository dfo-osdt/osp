<template>
    <q-card flat bordered>
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
            <div class="text-h3 text-weight-medium">{{ showValue }}</div>
        </q-card-section>
        <q-card-section class="q-pt-none">
            <div class="text-body1 text-grey-8">{{ subtitle }}</div>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
// props
const props = defineProps<{
    title: string;
    value: number;
    subtitle: string;
    to?: object | string;
}>();

const showValue = computed(() => {
    if (props.value === 0) {
        return '0';
    }
    if (props.value == 10) {
        return '10+';
    }
    return props.value.toLocaleString();
});
</script>

<style scoped></style>
