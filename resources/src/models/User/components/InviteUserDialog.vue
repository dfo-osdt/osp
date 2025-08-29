<script setup lang="ts">
import type { AzureDirectoryUserResource } from '../AzureDirectoryUser'
import type { UserResource } from '../User'
import type { ErrorResponse } from '@/api/errors'
import type { Locale } from '@/stores/LocaleStore'
import { extractErrorMessages } from '@/api/errors'
import BaseDialog from '@/components/BaseDialog.vue'
import LocaleSelect from '@/components/LocaleSelect.vue'
import { UserService } from '../User'
import AzureUserSelect from './AzureUserSelect.vue'

const emits = defineEmits<{
  (event: 'created', user: UserResource): void
}>()
const localStore = useLocaleStore()
const authStore = useAuthStore()
const { t } = useI18n()

// azure directory integration
const enableAzureDirectory = ref(authStore.openAuthOnly)
const selectedAzureUser = ref<AzureDirectoryUserResource | null>(null)

// data
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const locale = ref<Locale>(localStore.locale)

watch(() => selectedAzureUser.value, (user) => {
  if (user) {
    firstName.value = user.data.first_name
    lastName.value = user.data.last_name
    email.value = user.data.email
    locale.value = user.data.locale
  }
})

// invitation logic
const loading = ref(false)
const errorMessage = ref<ErrorResponse | null>(null)

function onSubmit() {
  loading.value = true
  UserService.invite({
    first_name: firstName.value,
    last_name: lastName.value,
    email: email.value,
    locale: locale.value,
  })
    .then((user) => {
      emits('created', user)
    })
    .catch((error) => {
      errorMessage.value = extractErrorMessages(error)
    })
    .finally(() => {
      loading.value = false
    })
}

// rules
const nameRules = [(val: string) => !!val || t('common.required')]

const emailRules = [
  (val: string) => !!val || t('common.required'),
  (val: string) =>
    /.[^\n\r@\u2028\u2029]*@.+\..+/.test(val) || t('common.validation.email-invalid'),
]
</script>

<template>
  <BaseDialog :title="$t('invite-user-dialog.invite-a-user')">
    <q-card-section class="q-mx-md">
      <p>
        {{ $t('invite-user-dialog.help') }}
      </p>
    </q-card-section>
    <q-banner v-if="errorMessage" dark class="bg-negative">
      <template #avatar>
        <q-icon name="mdi-alert-circle-outline" />
      </template>
      <div>{{ errorMessage.message }}</div>
    </q-banner>
    <q-card-section v-if="enableAzureDirectory" class="q-mx-md">
      <div class="text-primary text-weight-bold">
        Search the DFO Directory
      </div>
      <p>Search the DFO user directory by email to invite the selected user.</p>
      <AzureUserSelect v-model="selectedAzureUser" />
    </q-card-section>
    <q-card-section class="q-mx-md">
      <q-form @submit.prevent="onSubmit">
        <q-input
          v-model="firstName"
          :label="$t('common.first-name')"
          :readonly="enableAzureDirectory"
          outlined
          :rules="nameRules"
        />
        <q-input
          v-model="lastName"
          :label="$t('common.last-name')"
          :readonly="enableAzureDirectory"
          outlined
          :rules="nameRules"
        />
        <q-input
          v-model="email"
          :label="$t('common.email')"
          :readonly="enableAzureDirectory"
          type="email"
          outlined
          :rules="emailRules"
        />
        <LocaleSelect
          v-model="locale"
          :hint="$t('invite-user-dialog.locale-hint')"
        />
        <q-card-actions align="right" class="q-mt-md">
          <q-btn
            v-close-popup
            :label="$t('common.cancel')"
            color="primary"
            flat
          />
          <q-btn
            type="submit"
            color="primary"
            :label="$t('invite-user-dialog.invite')"
            :loading="loading"
            :disable="loading"
          />
        </q-card-actions>
      </q-form>
    </q-card-section>
  </BaseDialog>
</template>

<style scoped></style>
