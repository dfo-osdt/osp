<script setup lang="ts">
import type { ErrorResponse } from '@/api/errors'
import type { SanctumStatusResponse } from '@/api/sanctum'
import { QForm } from 'quasar'
import { useSanctum } from '@/api/sanctum'
import ContentCard from '@/components/ContentCard.vue'
import PasswordWithToggleInput from '@/components/PasswordWithToggleInput.vue'

const password = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')

const { t } = useI18n()
const localeStore = useLocaleStore()
const authStore = useAuthStore()
const sanctum = useSanctum()
const loading = ref(false)
const errorMessage = ref<ErrorResponse | null>(null)
const changed = ref<SanctumStatusResponse | null>(null)
const form = ref<QForm | null>(null)

async function changePassword() {
  loading.value = true
  errorMessage.value = null
  changed.value = null

  await sanctum
    .changePassword({
      current_password: password.value,
      password: newPassword.value,
      password_confirmation: newPasswordConfirmation.value,
      locale: localeStore.locale,
    })
    .then((response) => {
      changed.value = response.data
      form.value?.reset()
    })
    .catch((error) => {
      errorMessage.value = error.data
    })
    .finally(() => {
      authStore.refreshUser()
      loading.value = false
    })

  console.log('changePassword')
}

function onReset() {
  // clear form
  password.value = ''
  newPassword.value = ''
  newPasswordConfirmation.value = ''
}

// rules
const passwordRules = computed(() => [
  (v: string) => !!v || t('common.validation.password-required'),
])

const newPasswordRules = computed(() => [
  (v: string) => !!v || t('common.validation.password-required'),
  (v: string) => v.length >= 12 || t('common.validation.password-length'),
  // not the same as current password
  (v: string) =>
    v !== password.value || t('common.validation.passwords-different'),
])

const newPasswordConfirmationRules = computed(() => [
  (v: string) => !!v || t('common.validation.password-required'),
  (v: string) =>
    v === newPassword.value || t('common.validation.passwords-match'),
])
</script>

<template>
  <ContentCard secondary>
    <template #title>
      {{ $t('change-password-card.title') }}
    </template>
    <template #subtitle>
      {{ $t('change-password-card.subtitle') }}
    </template>
    <q-banner
      v-if="authStore.user?.new_password_required"
      rounded
      class="bg-warning text-white q-mx-md q-mb-md"
    >
      <template #avatar>
        <q-icon name="mdi-lock-alert-outline" />
      </template>
      {{ $t('change-password-card.new-password-required') }}
    </q-banner>
    <q-banner
      v-if="errorMessage"
      rounded
      class="bg-negative text-white q-mx-md"
    >
      <template #avatar>
        <q-icon name="mdi-alert-circle-outline" />
      </template>
      <div>{{ errorMessage?.message }}</div>
    </q-banner>
    <q-banner v-if="changed" rounded class="bg-positive text-white q-mx-md">
      <template #avatar>
        <q-icon name="mdi-lock-check-outline" />
      </template>
      {{ changed.status }}
    </q-banner>
    <q-card-section>
      <QForm ref="form" @submit="changePassword" @reset="onReset">
        <PasswordWithToggleInput
          v-model="password"
          :label="$t('common.current-password')"
          :rules="passwordRules"
          filled
          class="q-mb-md"
        />
        <PasswordWithToggleInput
          v-model="newPassword"
          :label="$t('change-password-card.new-password')"
          :rules="newPasswordRules"
          filled
          class="q-mb-md"
        />
        <PasswordWithToggleInput
          v-model="newPasswordConfirmation"
          :label="$t('change-password-card.confirm-new-password')"
          :rules="newPasswordConfirmationRules"
          filled
          class="q-mb-md"
        />
        <q-card-actions align="right">
          <q-btn
            type="submit"
            color="primary"
            :label="$t('change-password-card.change-password')"
            class="q-mt-md"
            :loading="loading"
          />
        </q-card-actions>
      </QForm>
    </q-card-section>
  </ContentCard>
</template>

<style scoped></style>
