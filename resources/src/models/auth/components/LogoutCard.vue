<script setup lang="ts">
const authStore = useAuthStore()
const router = useRouter()

// UI related data
const loading = ref(false)
const inactive = ref(router.currentRoute.value.query.inactive)

async function logout() {
  loading.value = true
  await authStore.logout()
  loading.value = false
}

onMounted(async () => {
  logout()
})
</script>

<template>
  <q-card flat>
    <q-card-section>
      <h1 class="text-h2 text-primary q-mb-xs text-center">
        {{ $t('common.logged-out') }}
      </h1>
      <p class="text-body2 text-grey-8 text-center">
        {{ $t('logout-card.text') }}
      </p>
      <q-banner
        v-if="inactive"
        rounded
        dark
        class="text-body2 text-center bg-primary"
      >
        <template #avatar>
          <q-icon name="mdi-timer-outline" />
        </template>
        {{ $t('logout-card.text-inactive') }}
      </q-banner>
    </q-card-section>
    <q-card-actions class="flex justify-around">
      <q-btn
        round
        outline
        color="primary"
        data-test="login"
        :to="{ name: 'login' }"
        icon="mdi-home"
      />
    </q-card-actions>
  </q-card>
</template>

<style scoped></style>
