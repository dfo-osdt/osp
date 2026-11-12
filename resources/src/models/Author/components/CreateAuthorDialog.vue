<script setup lang="ts">
import type { AxiosResponse } from 'axios'
import type { AuthorCreate, AuthorResource, OrcidAuthorImport } from '../Author'
import { extractErrorMessages } from '@/api/errors'
import BaseDialog from '@/components/BaseDialog.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import OrcidInput from '@/components/OrcidInput.vue'
import { useDefaultOrganizationId } from '@/composables/useDefaultOrganizationId'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { AuthorService } from '../Author'
import AuthorDfoProfileFields from './AuthorDfoProfileFields.vue'
import OrcidAuthorSearchDialog from './OrcidAuthorSearchDialog.vue'

// "created" also carries an existing author picked from an ORCID search instead of creating a duplicate
const emit = defineEmits<{
  (event: 'created', payload: AuthorResource): void
}>()

const { t } = useI18n()

const firstName = ref('')
const lastName = ref('')
const email = ref('')
const organizationId = ref<number | null>(null)
const isIndependent = ref(false)
const orcId = ref('')
const sectorId = ref<number | null>(null)
const regionId = ref<number | null>(null)
const isEmeritus = ref(false)
const errorMessage = ref('')
const showPersonalEmailWarning = ref(false)
const loading = ref(false)

const defaultOrganizationId = useDefaultOrganizationId()

// Sector, region and emeritus status only apply to DFO authors
const isInternal = computed(() => !isIndependent.value && organizationId.value === defaultOrganizationId)

watch(isInternal, (internal) => {
  if (!internal) {
    sectorId.value = null
    regionId.value = null
    isEmeritus.value = false
  }
})

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
    showPersonalEmailWarning.value = !isIndependent.value && personalEmailDomains.includes(emailDomain)
  }
  else {
    showPersonalEmailWarning.value = false
  }
})

const showOrcidSearch = ref(false)
// what the form held before an ORCID import, to put back on clear
const beforeOrcidImport = ref<{ firstName: string, lastName: string, email: string, organizationId: number | null, orcId: string } | null>(null)
const orcidAffiliationName = ref<string | null>(null)
// remounts the organization select so it loads an imported organization
const organizationSelectKey = ref(0)

const orcidSearchQuery = computed(() => `${firstName.value} ${lastName.value}`.trim())

function importFromOrcid(imported: OrcidAuthorImport) {
  beforeOrcidImport.value ??= {
    firstName: firstName.value,
    lastName: lastName.value,
    email: email.value,
    organizationId: organizationId.value,
    orcId: orcId.value,
  }
  firstName.value = imported.first_name ?? firstName.value
  lastName.value = imported.last_name ?? lastName.value
  email.value = imported.email ?? email.value
  orcId.value = imported.orcid
  if (imported.organization_id !== null) {
    organizationId.value = imported.organization_id
    isIndependent.value = false
  }
  orcidAffiliationName.value = imported.organization_id === null
    ? imported.affiliation_name
    : null
  organizationSelectKey.value++
  showOrcidSearch.value = false
}

function clearOrcidImport() {
  if (!beforeOrcidImport.value) {
    return
  }
  firstName.value = beforeOrcidImport.value.firstName
  lastName.value = beforeOrcidImport.value.lastName
  email.value = beforeOrcidImport.value.email
  organizationId.value = beforeOrcidImport.value.organizationId
  orcId.value = beforeOrcidImport.value.orcId
  beforeOrcidImport.value = null
  orcidAffiliationName.value = null
  organizationSelectKey.value++
}

async function useExistingAuthor(authorId: number) {
  showOrcidSearch.value = false
  emit('created', await AuthorService.find(authorId))
}

async function createAuthor() {
  if (!isIndependent.value && organizationId.value === null) {
    return
  }

  loading.value = true
  const data: AuthorCreate = {
    first_name: firstName.value,
    last_name: lastName.value,
    email: email.value,
    ...(isIndependent.value
      ? { is_independent: true }
      : { organization_id: organizationId.value as number }),
    orcid: orcId.value,
    ...(isInternal.value && {
      sector_id: sectorId.value,
      region_id: regionId.value,
      is_emeritus: isEmeritus.value,
    }),
    ...(beforeOrcidImport.value && { source: 'orcid_search' as const }),
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
    <!-- these are a co-author's details, not the user's own, so the browser should not offer to save them -->
    <q-form autocomplete="off" @submit="createAuthor">
      <!-- Fill from ORCID -->
      <q-banner rounded dense class="bg-grey-2 q-mx-md q-mt-md">
        <template #avatar>
          <OrcidIcon class="q-mt-xs" />
        </template>
        <q-chip
          v-if="beforeOrcidImport"
          removable
          color="green-1"
          text-color="green-10"
          icon="mdi-check"
          class="q-ml-none"
          @remove="clearOrcidImport"
        >
          {{ $t('create-author-dialog.filled-from-orcid') }}
          <a
            :href="orcId"
            target="_blank"
            rel="noopener noreferrer"
            class="q-ml-xs"
          >
            {{ orcId.split('/').pop() }}
            <q-icon name="mdi-open-in-new" />
          </a>
          <q-tooltip class="text-body2">
            {{ $t('create-author-dialog.clear-orcid-import') }}
          </q-tooltip>
        </q-chip>
        <span v-else class="text-body2">
          {{ $t('create-author-dialog.fill-from-orcid-intro') }}
        </span>
        <template v-if="!beforeOrcidImport" #action>
          <q-btn
            outline
            color="primary"
            :label="$t('create-author-dialog.fill-from-orcid')"
            @click="showOrcidSearch = true"
          />
        </template>
      </q-banner>

      <!-- Personal Information Group -->
      <q-card flat bordered class="q-ma-md">
        <q-card-section>
          <div class="text-subtitle2 text-grey-8 q-mb-md">
            {{ $t('create-author-dialog.personal-information') }}
          </div>
          <q-input
            v-model="firstName"
            outlined
            autocomplete="off"
            :label="$t('common.first-name')"
            class="q-mb-md"
            :rules="[(val: string) => val !== '' || t('common.required')]"
          />
          <q-input
            v-model="lastName"
            outlined
            autocomplete="off"
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
            autocomplete="off"
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

          <q-toggle
            v-model="isIndependent"
            :label="$t('common.independent-researcher')"
          />
          <div class="text-caption text-grey-7 q-mb-md">
            {{ $t('common.independent-researcher-hint') }}
          </div>

          <div v-if="!isIndependent && orcidAffiliationName" class="text-caption text-orange-9 q-mb-sm">
            {{ $t('create-author-dialog.orcid-lists-affiliation', { name: orcidAffiliationName }) }}
          </div>
          <OrganizationSelect
            v-if="!isIndependent"
            :key="organizationSelectKey"
            v-model="organizationId"
            :show-default-organization="!beforeOrcidImport"
            :initial-search-term="orcidAffiliationName ?? undefined"
            :label="$t('common.affiliation')"
            class="q-mb-md"
            :rules="[
              (val: number | null) => val !== null || t('common.required'),
            ]"
          />

          <AuthorDfoProfileFields
            v-if="isInternal"
            v-model:sector-id="sectorId"
            v-model:region-id="regionId"
            v-model:is-emeritus="isEmeritus"
            :required="false"
            class="q-mb-md"
          />

          <OrcidInput
            v-model="orcId"
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
    <OrcidAuthorSearchDialog
      v-if="showOrcidSearch"
      v-model="showOrcidSearch"
      :initial-query="orcidSearchQuery"
      @import="importFromOrcid"
      @use-existing="useExistingAuthor"
    />
  </BaseDialog>
</template>

<style scoped></style>
