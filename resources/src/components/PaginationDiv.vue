<template>
    <div v-if="meta" class="row justify-between">
        <div class="text-body2 text-grey-6 q-mt-xs">
            <div v-if="meta.total >= 1">
                Showing: {{ meta.from }} - {{ meta.to }} of {{ meta.total }}
            </div>
        </div>
        <q-pagination
            v-if="meta.last_page > 1"
            v-model="currentPage"
            :max="meta.last_page"
            :disable="disable"
            direction-links
        >
        </q-pagination>
    </div>
</template>

<script setup lang="ts">
import { Meta } from '@/models/Resource';

const props = defineProps<{
    modelValue: number;
    meta: Readonly<Meta> | null;
    disable?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);
const currentPage = useVModel(props, 'modelValue', emit);
</script>

<style scoped></style>
