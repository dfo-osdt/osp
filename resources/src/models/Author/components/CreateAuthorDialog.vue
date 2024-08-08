<script setup lang="ts">
import type { AxiosResponse } from 'axios'
import type { Author, AuthorResource } from '../Author'
import { AuthorService } from '../Author'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import BaseDialog from '@/components/BaseDialog.vue'
import OrcidInput from '@/components/OrcidInput.vue'
import { extractErrorMessages } from '@/api/errors'

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

async function createAuthor() {
  if (organizationId.value === null) {
    return
  }

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
  }
}
</script>

<template>
  <BaseDialog persistent :title="$t('create-author-dialog.title')">
    <q-banner v-if="errorMessage" class="q-pa-md bg-red text-white">
      {{
        errorMessage
      }}
    </q-banner>
    <q-form @submit="createAuthor">
      <q-input
        v-model="firstName"
        outlined
        :label="$t('common.first-name')"
        class="q-ma-md"
        :rules="[(val: string) => val !== '' || t('common.required')]"
      />
      <q-input
        v-model="lastName"
        outlined
        :label="$t('common.last-name')"
        class="q-ma-md"
        :rules="[(val: string) => val !== '' || t('common.required')]"
      />
      <OrganizationSelect
        v-model="organizationId"
        :label="$t('common.affiliation')"
        class="q-ma-md"
        :rules="[
          (val: number | null) =>
            val !== null || t('common.required'),
        ]"
      />
      <q-input
        v-model="email"
        outlined
        :label="$t('common.email')"
        class="q-ma-md"
        :rules="[
          (val: string) => val !== '' || t('common.required'),
          (val: string) =>
            /^\S+@\S+\.\S+$/.test(val)
            || t('common.validation.email-invalid'),
        ]"
      />
      <OrcidInput
        v-model.stripBaseUrl="orcId"
        class="q-ma-md"
        :hint="$t('common.validation.orcid-hint')"
      />
      <div class="flex justify-end">
        <q-btn
          color="primary"
          :label="$t('common.create')"
          type="submit"
          class="q-ma-md"
        />
      </div>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
