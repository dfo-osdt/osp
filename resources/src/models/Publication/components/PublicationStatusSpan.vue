<template>
    <span>
        {{ statusLabel }}
    </span>
</template>

<script setup lang="ts">
import { PublicationStatus } from '../Publication';

const t = useI18n();

const props = defineProps<{
    status: PublicationStatus;
}>();

const statusLabel = computed(() => {
    switch (props.status) {
        case 'accepted':
            return t.t('publication.accepted');
        case 'published':
            return t.t('publication.published');
    }
});
</script>

<style scoped></style>
