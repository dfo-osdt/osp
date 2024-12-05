<script setup lang="ts">
const { unauthenticated = false } = defineProps<{
  unauthenticated?: boolean
}>()

const svgLink = computed(() => {
  return unauthenticated
    ? 'img:/assets/orcid/orcid.logo.unauth.svg'
    : 'img:/assets/orcid/orcid.logo.icon.svg'
})
</script>

<template>
  <q-avatar :icon="svgLink" aria-label="ORCID iD logo">
    <q-tooltip anchor="top middle" self="bottom middle">
      <span v-if="unauthenticated">
        {{ $t('common.unauthenticated-orcid-id') }}
      </span>
    </q-tooltip>
  </q-avatar>
</template>

<style scoped></style>
