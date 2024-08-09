<script setup lang="ts">
import type { UserResource } from '../User'
import { UserService } from '../User'
import type { ErrorResponse } from '@/api/errors'
import { extractErrorMessages } from '@/api/errors'
import BaseDialog from '@/components/BaseDialog.vue'
import LocaleSelect from '@/components/LocaleSelect.vue'
import type { Locale } from '@/stores/LocaleStore'

const emits = defineEmits<{
  (event: 'created', user: UserResource): void
}>()
const localStore = useLocaleStore()
const { t } = useI18n()

// data
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const locale = ref<Locale>(localStore.locale)

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
    <q-card-section>
      <p class="q-ma-md">
        {{ $t('invite-user-dialog.help') }}
      </p>
    </q-card-section>
    <q-banner v-if="errorMessage" dark class="bg-negative">
      <template #avatar>
        <q-icon name="mdi-alert-circle-outline" />
      </template>
      <div>{{ errorMessage.message }}</div>
    </q-banner>
    <q-card-section class="q-mx-md">
      <q-form @submit.prevent="onSubmit">
        <q-input
          v-model="firstName"
          :label="$t('common.first-name')"
          outlined
          :rules="nameRules"
        />
        <q-input
          v-model="lastName"
          :label="$t('common.last-name')"
          outlined
          :rules="nameRules"
        />
        <q-input
          v-model="email"
          :label="$t('common.email')"
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
