<script setup lang="ts">
import type { ReviewContributorRole } from '../reviewContributorRoleOptions'
import { useReviewContributorRoleOptions } from '../reviewContributorRoleOptions'

const value = defineModel<ReviewContributorRole>()

const { options } = useReviewContributorRoleOptions()
</script>

<template>
  <q-select
    v-model="value"
    :options="options"
    :label="$t('manage-review-contributors.role')"
    outlined
    emit-value
    map-options
  />
</template>

<style scoped></style>
