<script setup lang="ts">
import type { AxiosResponse } from 'axios'
import type { Author, AuthorResource } from '../Author'
import { extractErrorMessages } from '@/api/errors'
import BaseDialog from '@/components/BaseDialog.vue'
import OrcidInput from '@/components/OrcidInput.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { AuthorService } from '../Author'

const props = defineProps<{
  author: AuthorResource
}>()

const emit = defineEmits<{
  (event: 'updated', payload: AuthorResource): void
}>()

const authStore = useAuthStore()
const { t } = useI18n()

const firstName = ref(props.author.data.first_name)
const lastName = ref(props.author.data.last_name)
const email = ref(props.author.data.email)
const organizationId = ref<number | null>(props.author.data.organization_id)
const orcId = ref(props.author.data.orcid || '')
const syncAllPivots = ref(false)
const errorMessage = ref('')
const showPersonalEmailWarning = ref(false)

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

// Check if author has a linked user account
const hasUserAccount = computed(() => {
  return props.author.data.user_id !== null
})

// Check if user has permission to sync all pivots
const canSyncAllPivots = computed(() => {
  return authStore.user?.can('update_authors') ?? false
})

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

async function updateAuthor() {
  if (organizationId.value === null) {
    return
  }

  const data: Partial<Author> & { sync_all_pivots?: boolean } = {
    id: props.author.data.id,
    first_name: firstName.value,
    last_name: lastName.value,
    email: email.value,
    organization_id: organizationId.value,
    orcid: orcId.value,
    sync_all_pivots: syncAllPivots.value,
  }

  try {
    const updatedAuthor = await AuthorService.update(data as Author)
    emit('updated', updatedAuthor)
  }
  catch (error) {
    if (error && typeof error === 'object' && 'data' in error) {
      const response = error as AxiosResponse
      const errMsg = extractErrorMessages(response)
      errorMessage.value = errMsg.message
    }
  }
}
</script>

<template>
  <BaseDialog :title="$t('edit-author-dialog.title')">
    <q-banner v-if="errorMessage" class="q-pa-md bg-red text-white">
      {{ errorMessage }}
    </q-banner>
    <q-banner v-if="hasUserAccount" class="q-pa-md bg-blue-2 text-blue-9">
      <div class="flex items-start">
        <q-icon name="mdi-information" size="sm" class="q-mr-sm" />
        <div>
          {{ $t('edit-author-dialog.user-account-warning') }}
        </div>
      </div>
    </q-banner>
    <q-form @submit="updateAuthor">
      <!-- Personal Information Group -->
      <q-card flat bordered class="q-ma-md">
        <q-card-section>
          <div class="text-subtitle2 text-grey-8 q-mb-md">
            {{ $t('edit-author-dialog.personal-information') }}
          </div>
          <q-input
            v-model="firstName"
            outlined
            :label="$t('common.first-name')"
            class="q-mb-md"
            :disable="hasUserAccount"
            :rules="[(val: string) => val !== '' || t('common.required')]"
          />
          <q-input
            v-model="lastName"
            outlined
            :label="$t('common.last-name')"
            class="q-mb-md"
            :disable="hasUserAccount"
            :rules="[(val: string) => val !== '' || t('common.required')]"
          />

          <!-- Personal Email Warning -->
          <div v-if="showPersonalEmailWarning" class="q-mb-sm">
            <div class="text-caption text-orange-8">
              {{ $t('edit-author-dialog.personal-email-warning') }}
            </div>
          </div>

          <q-input
            v-model="email"
            outlined
            :label="$t('common.email')"
            class="q-mb-md"
            :disable="hasUserAccount"
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
            {{ $t('edit-author-dialog.affiliation-research') }}
          </div>

          <!-- ROR Guidance -->
          <div class="q-mb-sm">
            <div class="text-caption text-grey-7">
              {{ $t('edit-author-dialog.ror-guidance') }}
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
            :disable="author.data.orcid_verified"
          />

          <div v-if="author.data.orcid_verified" class="q-mt-sm">
            <div class="text-caption text-grey-7">
              {{ $t('edit-author-dialog.orcid-verified-note') }}
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Sync Options (only for users with update_authors permission) -->
      <q-card v-if="canSyncAllPivots" flat bordered class="q-ma-md">
        <q-card-section>
          <div class="text-subtitle2 text-grey-8 q-mb-md">
            {{ $t('edit-author-dialog.update-options') }}
          </div>
          <q-checkbox
            v-model="syncAllPivots"
            :label="$t('edit-author-dialog.sync-all-pivots')"
          >
            <q-tooltip max-width="300px">
              {{ $t('edit-author-dialog.sync-all-pivots-tooltip') }}
            </q-tooltip>
          </q-checkbox>
        </q-card-section>
      </q-card>

      <q-card-actions class="justify-end">
        <q-btn
          v-close-popup
          :label="$t('common.cancel')"
          color="primary"
          outline
        />
        <q-btn
          color="primary"
          :label="$t('common.save')"
          type="submit"
        />
      </q-card-actions>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
