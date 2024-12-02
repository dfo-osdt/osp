<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue'
import DateInput from '@/components/DateInput.vue'
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue'
import { useQuasar } from 'quasar'
import { AuthorEmploymentService } from '../AuthorEmployement'

const props = defineProps<{
  authorId: number
}>()

const emit = defineEmits<{
  changed: []
  deleted: []
}>()

// form data
// eslint-disable-next-line prefer-const -- used as v-model in the template
let organizationId = 1
const roleTitle = ref<string | null>(null)
const departmentName = ref<string | null>(null)
const startDate = ref<string | null>(null)
const endDate = ref<string | null>(null)

const { t } = useI18n()
const q = useQuasar()

const authorEmploymentService = new AuthorEmploymentService(props.authorId)

const today = new Date().toISOString().split('T')[0]

async function createAuthorEmployment() {
  q.dialog({
    title: t('orcid-employment-edit.update-confirmation-title'),
    message: t('orcid-employment-edit.update-confirmation-message'),
    ok: {
      label: t('common.confirm'),
      color: 'primary',
    },
    cancel: true,
  }).onOk(async () => {
    await authorEmploymentService.create({
      author_id: props.authorId,
      organization_id: organizationId,
      role_title: roleTitle.value,
      department_name: departmentName.value,
      start_date: startDate.value as string,
      end_date: endDate.value,
    })
    emit('changed')
  })
}
</script>

<template>
  <BaseDialog :title="t('orcid-employment-add.dialog-title')">
    <q-card-section>
      {{ t('orcid-employment-edit.dialog-description') }}
    </q-card-section>
    <q-form @submit="createAuthorEmployment">
      <q-card-section>
        <OrganizationSelect v-model="organizationId" :label="t('common.organization')" outlined disable hide-hint class="q-mb-md" />
        <q-input
          v-model="roleTitle"
          :label="t('orcid-employment-edit.role-title')"
          outlined
          :rules="[(val: string|null) => !!val || t('common.required'),
                   (val: string) => val.length <= 255 || t('common.validation.must-be-less-than-x-characters', [255]),
          ]"
          class="q-mb-md"
        />
        <q-input
          v-model="departmentName"
          :label="t('orcid-employment-edit.department-name')"
          outlined
          :rules="[(val: string|null) => !!val || t('common.required'),
                   (val: string) => val.length <= 255 || t('common.validation.must-be-less-than-x-characters', [255]),
          ]"
          class="q-mb-md"
          :hint="t('orcid-employment-edit.department-name-hint')"
          :hide-hint="false"
        />
        <DateInput
          v-model="startDate"
          :label="t('orcid-employment-edit.start-date')"
          :max-date="today"
          outlined
          :rules="[(val : string) => !!val || t('common.required')]"
          class="q-mb-md"
        />
        <DateInput
          v-model="endDate"
          :label="t('orcid-employment-edit.end-date')"
          :min-date="startDate"
          outlined
          clearable
          class="q-mb-md"
        />
      </q-card-section>
      <q-card-actions class="justify-end">
        <q-btn v-close-popup :label="$t('common.cancel')" color="primary" outline />
        <q-btn
          type="submit"
          color="primary"
          :label="$t('common.create')"
        />
      </q-card-actions>
    </q-form>
  </BaseDialog>
</template>

<style scoped>

</style>
