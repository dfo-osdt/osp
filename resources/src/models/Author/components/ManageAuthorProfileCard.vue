<script setup lang="ts">
import type { Ref } from 'vue'
import type { AuthorResource } from '../Author'
import { QForm, QInput, useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import OrcidInput from '@/components/OrcidInput.vue'
import SensitivityLabelChip from '@/components/SensitivityLabelChip.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { AuthorService } from '../Author'
import AuthorDfoProfileFields from './AuthorDfoProfileFields.vue'

const props = defineProps<{
  authorId: number
}>()
const $q = useQuasar()
const { t } = useI18n()
const authStore = useAuthStore()
const defaultOrganizationId = useDefaultOrganizationId()
const form = ref<QForm | null>(null)
const loading = ref(true)
const author: Ref<AuthorResource | null> = ref(null)

const hasOwner = computed(() => {
  return author.value?.data.user_id !== null
})

const isOwnAuthor = computed(() => hasOwner.value && author.value?.data.user_id === authStore.user?.id)

// Sector, region and emeritus status only apply to DFO authors
const isInternal = computed(() => !author.value?.data.is_independent && author.value?.data.organization_id === defaultOrganizationId)

async function getAuthor() {
  loading.value = true
  author.value = await AuthorService.find(props.authorId)
  loading.value = false
}

const orcidInputHint = computed(() => {
  if (author.value?.data.orcid_verified) {
    return t('manage-author-profile-card.orcid-verified')
  }
  return t('manage-author-profile-card.orcid-not-verified')
})

// Leaving independent status requires picking a real affiliation
function onIndependentChange(independent: boolean) {
  if (!independent && author.value) {
    author.value.data.organization_id = null as unknown as number
  }
}

onMounted(async () => {
  getAuthor()
})

async function save() {
  if (!author.value)
    return
  loading.value = true
  await AuthorService.update(author.value.data)
    .then((resp) => {
      $q.notify({
        type: 'positive',
        message: t('common.saved'),
      })
      author.value = resp
      if (isOwnAuthor.value) {
        authStore.refreshUser(true)
      }
    })
    .catch((err) => {
      console.error(err)
    })
    .finally(() => {
      loading.value = false
    })
}
</script>

<template>
  <ContentCard>
    <template #title>
      {{ t('SettingsPage.author-profile') }}
    </template>
    <template #title-right>
      <SensitivityLabelChip sensitivity="Protected A" />
    </template>
    <template v-if="author">
      <QForm ref="form" @submit="save">
        <div class="row q-col-gutter-md">
          <QInput
            v-model="author.data.first_name"
            class="col-12 col-md-6"
            :label="t('common.first-name')"
            outlined
            :disable="hasOwner"
            :rules="[
              (val) => val.length > 0 || t('common.required'),
            ]"
            :show-hint="hasOwner"
            :hint="
              t(
                'manage-author-profile-card.synced-to-your-user-profile',
              )
            "
          />
          <QInput
            v-model="author.data.last_name"
            class="col-12 col-md-6"
            :label="$t('common.last-name')"
            outlined
            :disable="hasOwner"
            :rules="[
              (val) => val.length > 0 || t('common.required'),
            ]"
            :show-hint="hasOwner"
            :hint="
              t(
                'manage-author-profile-card.synced-to-your-user-profile',
              )
            "
          />
          <QInput
            v-model="author.data.email"
            class="col-12 col-md-6"
            :label="t('common.email')"
            outlined
            :disable="true"
            :show-hint="hasOwner"
            :hint="
              t(
                'manage-author-profile-card.synced-to-your-user-profile',
              )
            "
          />
          <div class="col-12">
            <q-toggle
              v-model="author.data.is_independent"
              :label="t('common.independent-researcher')"
              :disable="hasOwner"
              @update:model-value="onIndependentChange"
            />
            <div class="text-caption text-grey-7">
              {{ t('common.independent-researcher-hint') }}
            </div>
          </div>
          <OrganizationSelect
            v-if="!author.data.is_independent"
            v-model="author.data.organization_id"
            class="col-12 col-md-6"
            :label="t('common.current-affiliation')"
            outlined
            :disable="hasOwner"
            :hint="
              t(
                'manage-author-profile-card.current-affiliation-hint',
              )
            "
            clearable
            :rules="[
              (val: number | null) =>
                val !== null || t('common.required'),
            ]"
          />
          <AuthorDfoProfileFields
            v-if="isInternal"
            v-model:sector-id="author.data.sector_id"
            v-model:region-id="author.data.region_id"
            v-model:is-emeritus="author.data.is_emeritus"
            :required="isOwnAuthor"
            class="col-12"
          />
          <OrcidInput
            v-model.stripBaseUrl="author.data.orcid"
            :disable="author.data.orcid_verified"
            :hint="orcidInputHint"
            class="col-12 col-md-6"
          />
        </div>
        <q-card-actions align="right">
          <q-btn
            color="primary"
            :label="t('common.save')"
            :loading="loading"
            type="submit"
          />
        </q-card-actions>
      </QForm>
    </template>
  </ContentCard>
</template>

<style scoped></style>
