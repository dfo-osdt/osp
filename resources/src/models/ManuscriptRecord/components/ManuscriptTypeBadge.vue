<template>
    <q-badge outline color="primary">
        {{ typeLabel }}
    </q-badge>
</template>

<script setup lang="ts">
import { ManuscriptRecordType } from '../ManuscriptRecord';

const { t } = useI18n();

const props = defineProps<{
    type: ManuscriptRecordType;
}>();

const typeLabel = computed(() => {
    switch (props.type) {
        case 'primary':
            return t('manuscript.primary');
        case 'secondary':
            return t('manuscript.secondary');
    }
});
</script>

<style scoped></style>
