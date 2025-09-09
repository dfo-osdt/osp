<script setup lang="ts">
import type { OrganizationResource } from '../Organization'

defineProps<{
  organization: OrganizationResource | undefined
}>()
</script>

<template>
  <span v-if="organization?.data?.ror_identifier">
    {{ $t('RORLinkSpan.ror-id-label') }}
    <a
      :href="organization.data.ror_identifier"
      target="_blank"
      rel="noopener noreferrer"
    >
      {{ organization.data.ror_identifier }}
    </a>
  </span>
</template>

<style scoped></style>
