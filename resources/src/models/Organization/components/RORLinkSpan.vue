<template>
    <span v-if="organization?.data?.ror_identifier">
        ROR ID:
        <a
            :href="organization.data.ror_identifier"
            target="_blank"
            rel="noopener noreferrer"
        >
            {{ organization.data.ror_identifier }}
        </a>
    </span>
</template>

<script setup lang="ts">
import { OrganizationResource } from '../Organization';

defineProps<{
    organization: OrganizationResource | undefined;
}>();
</script>

<style scoped></style>
