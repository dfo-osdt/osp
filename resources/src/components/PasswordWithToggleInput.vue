<script setup lang="ts">
const isPwd = ref(true)
const localPassword = ref('')

/**
 * Toggle password visibility - hides password again after 5 seconds
 */
function show() {
  if (!isPwd.value) {
    stop()
    isPwd.value = true
    return
  }
  isPwd.value = false
  start()
}

const { start, stop } = useTimeoutFn(() => {
  isPwd.value = true
}, 5000)
</script>

<template>
  <q-input v-model="localPassword" :type="isPwd ? 'password' : 'text'">
    <template #append>
      <q-icon
        :name="isPwd ? 'mdi-eye-off' : 'mdi-eye'"
        class="cursor-pointer"
        @click="show()"
      >
        <q-tooltip>
          {{
            isPwd ? $t('common.show') : $t('common.hide')
          }}
        </q-tooltip>
      </q-icon>
    </template>
  </q-input>
</template>

<style scoped></style>
