<script setup lang="ts">
import { onBeforeRouteLeave } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useQuasar } from 'quasar'

const props = defineProps<{
  isDirty: boolean
}>()
const { t } = useI18n()
const $q = useQuasar()
const authStore = useAuthStore()

// warn the user if they try to leave the page while there are unsaved changes
onBeforeRouteLeave((to, from, next) => {
  // if user is logged out ... don't warn them
  if (!authStore.isAuthenticated) {
    next()
    return
  }

  if (props.isDirty) {
    $q.dialog({
      title: t('dialog.unsaved-changes.title'),
      message: t('dialog.unsaved-changes.message'),
      cancel: true,
      persistent: true,
    }).onOk(() => {
      next()
    })
  }
  else {
    next()
  }
})
</script>

<template>
  <div>
    <slot />
  </div>
</template>

<style scoped></style>
