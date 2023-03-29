<template>
    <q-card outlined flat bordered>
        <q-card-section class="text-primary" :class="bgClass">
            <div
                class="row flex justify-between"
                :class="secondary ? 'text-h6 text-weight-regular' : 'text-h5'"
            >
                <div>
                    <slot name="title">Title here</slot>
                    <div class="text-subtitle2 text-grey-6">
                        <slot name="subtitle" />
                    </div>
                </div>
                <div><slot name="title-right"></slot></div>
            </div>
        </q-card-section>
        <q-separator v-if="secondary" />
        <slot name="nav"></slot>
        <q-card-section :class="noPadding ? 'q-pa-none' : ''">
            <slot></slot>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        secondary?: boolean;
        noPadding?: boolean;
    }>(),
    {
        secondary: false,
    }
);

const bgClass = computed(() => {
    return props.secondary ? '' : 'bg-teal-1';
});
</script>

<style scoped></style>
