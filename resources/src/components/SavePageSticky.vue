<script setup lang="ts">
defineProps<{
  isDirty: boolean
  loading: boolean
  hideOnScroll: boolean
}>()

const emit = defineEmits(['save'])
const { isScrolling } = useScroll(window)
</script>

<template>
  <q-page-sticky
    v-show="hideOnScroll ? !isScrolling : true"
    position="bottom-right"
    :offset="[18, 18]"
  >
    <q-btn
      fab
      :icon="
        isDirty
          ? 'mdi-content-save-alert-outline'
          : 'mdi-content-save-outline'
      "
      color="primary"
      :loading="loading"
      @click="emit('save')"
    />
  </q-page-sticky>
</template>

<style scoped>

</style>
