<script setup lang="ts">
import type { OrganizationResource } from '../Organization'

const props = defineProps<{
  organization?: OrganizationResource
  // a ROR from elsewhere, e.g. an affiliation on an ORCID record
  ror?: string | null
}>()

const rorIdentifier = computed(() => props.ror ?? props.organization?.data?.ror_identifier)
</script>

<template>
  <span v-if="rorIdentifier">
    {{ $t('RORLinkSpan.ror-id-label') }}
    <a
      :href="rorIdentifier"
      target="_blank"
      rel="noopener noreferrer"
    >
      {{ rorIdentifier }}
    </a>
  </span>
</template>

<style scoped></style>
