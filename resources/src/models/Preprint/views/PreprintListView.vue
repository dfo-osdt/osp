<script setup lang="ts">
import type { PreprintResourceList } from '../Preprint'
import { PreprintService } from '../Preprint'

const preprints = ref<PreprintResourceList | undefined>(undefined)

onMounted(() => {
  getPreprints()
})

async function getPreprints() {
  preprints.value = await PreprintService.list()
}
</script>

<template>
  <pre>{{ preprints }}</pre>
</template>

<style scoped>

</style>
