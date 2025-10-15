<script setup lang="ts">
import type { AxiosResponse } from 'axios'
import type { Author, AuthorResource } from '../Author'
import { extractErrorMessages } from '@/api/errors'
import BaseDialog from '@/components/BaseDialog.vue'
import OrcidInput from '@/components/OrcidInput.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { AuthorService } from '../Author'

const emit = defineEmits<{
  (event: 'created', payload: AuthorResource): void
}>()

const { t } = useI18n()

const firstName = ref('')
const lastName = ref('')
const email = ref('')
const organizationId = ref<number | null>(null)
const orcId = ref('')
const errorMessage = ref('')
const showPersonalEmailWarning = ref(false)
const loading = ref(false)

const personalEmailDomains = [
  'gmail.com',
  'yahoo.com',
  'hotmail.com',
  'outlook.com',
  'aol.com',
  'icloud.com',
  'protonmail.com',
  'yandex.com',
]

// Watch email input to detect personal email domains
watchEffect(() => {
  if (email.value) {
    const emailDomain = email.value.split('@')[1]?.toLowerCase()
    showPersonalEmailWarning.value = personalEmailDomains.includes(emailDomain)
  }
  else {
    showPersonalEmailWarning.value = false
  }
})

async function createAuthor() {
  if (organizationId.value === null) {
    return
  }

  loading.value = true
  const data: Partial<Author> = {
    first_name: firstName.value,
    last_name: lastName.value,
    email: email.value,
    organization_id: organizationId.value,
    orcid: orcId.value,
  }

  try {
    const author = await AuthorService.create(data)
    emit('created', author)
  }
  catch (error) {
    if (error && typeof error === 'object' && 'data' in error) {
      const response = error as AxiosResponse
      const errMsg = extractErrorMessages(response)
      errorMessage.value = errMsg.message
    }
    loading.value = false
  }
}
</script>

<template>
  <BaseDialog persistent :title="$t('create-author-dialog.title')">
    <q-banner v-if="errorMessage" class="q-pa-md bg-red text-white">
      {{ errorMessage }}
    </q-banner>
    <q-form @submit="createAuthor">
      <!-- Personal Information Group -->
      <q-card flat bordered class="q-ma-md">
        <q-card-section>
          <div class="text-subtitle2 text-grey-8 q-mb-md">
            {{ $t('create-author-dialog.personal-information') }}
          </div>
          <q-input
            v-model="firstName"
            outlined
            :label="$t('common.first-name')"
            class="q-mb-md"
            :rules="[(val: string) => val !== '' || t('common.required')]"
          />
          <q-input
            v-model="lastName"
            outlined
            :label="$t('common.last-name')"
            class="q-mb-md"
            :rules="[(val: string) => val !== '' || t('common.required')]"
          />

          <!-- Personal Email Warning -->
          <div v-if="showPersonalEmailWarning" class="q-mb-sm">
            <div class="text-caption text-orange-8">
              {{ $t('create-author-dialog.personal-email-warning') }}
            </div>
          </div>

          <q-input
            v-model="email"
            outlined
            :label="$t('common.email')"
            :rules="[
              (val: string) => val !== '' || t('common.required'),
              (val: string) =>
                /^\S+@\S+\.\S+$/.test(val)
                || t('common.validation.email-invalid'),
            ]"
          />
        </q-card-section>
      </q-card>

      <!-- Affiliation & Research Information Group -->
      <q-card flat bordered class="q-ma-md">
        <q-card-section>
          <div class="text-subtitle2 text-grey-8 q-mb-md">
            {{ $t('create-author-dialog.affiliation-research') }}
          </div>

          <!-- ROR Guidance -->
          <div class="q-mb-sm">
            <div class="text-caption text-grey-7">
              {{ $t('create-author-dialog.ror-guidance') }}
            </div>
          </div>

          <OrganizationSelect
            v-model="organizationId"
            show-default-organization
            :label="$t('common.affiliation')"
            class="q-mb-md"
            :rules="[
              (val: number | null) => val !== null || t('common.required'),
            ]"
          />

          <OrcidInput
            v-model.stripBaseUrl="orcId"
            :hint="$t('common.validation.orcid-hint')"
          />
        </q-card-section>
      </q-card>
      <div class="flex justify-end">
        <q-btn
          color="primary"
          :label="$t('common.create')"
          type="submit"
          class="q-ma-md"
          :loading="loading"
        />
      </div>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
