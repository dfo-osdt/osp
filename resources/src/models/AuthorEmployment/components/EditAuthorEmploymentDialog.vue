<script setup lang="ts">
import type { AuthorEmploymentResource } from '../AuthorEmployement'
import { useQuasar } from 'quasar'
import BaseDialog from '@/components/BaseDialog.vue'
import DateInput from '@/components/DateInput.vue'
import { AuthorEmploymentService } from '../AuthorEmployement'

const props = defineProps<{
  authorEmployment: AuthorEmploymentResource
}>()

const emit = defineEmits<{
  changed: []
  deleted: []
}>()

// form data
const roleTitle = ref(props.authorEmployment.data.role_title)
const departmentName = ref(props.authorEmployment.data.department_name)
const startDate = ref(props.authorEmployment.data.start_date)
const endDate = ref(props.authorEmployment.data.end_date)
const today = new Date().toISOString().split('T')[0]

const { t } = useI18n()
const q = useQuasar()

const authorEmploymentService = new AuthorEmploymentService(props.authorEmployment.data.author_id)
const loading = ref(false)

async function udpateAuthorEmployment() {
  loading.value = true
  q.dialog({
    title: t('orcid-employment-edit.update-confirmation-title'),
    message: t('orcid-employment-edit.update-confirmation-message'),
    ok: {
      label: t('common.confirm'),
      color: 'primary',
    },
    cancel: true,
  }).onOk(async () => {
    await authorEmploymentService.update({
      id: props.authorEmployment.data.id,
      author_id: props.authorEmployment.data.author_id,
      organization_id: props.authorEmployment.data.organization_id,
      role_title: roleTitle.value,
      department_name: departmentName.value,
      start_date: startDate.value,
      end_date: endDate.value,
    })
    emit('changed')
  }).onCancel(() => {
    loading.value = false
  })
}

function deleteAuthorEmployment() {
  // confirm delete
  q.dialog({
    title: t('orcid-employment-edit.delete-confirmation-title'),
    message: t('orcid-employment-edit.delete-confirmation-message'),
    ok: {
      label: t('common.delete'),
      color: 'red',
    },
    cancel: true,
  }).onOk(async () => {
    // delete author employment
    await authorEmploymentService.delete(props.authorEmployment.data)
    emit('deleted')
  })
}
</script>

<template>
  <BaseDialog :title="t('orcid-employment-edit.dialog-title')">
    <q-card-section>
      {{ t('orcid-employment-edit.dialog-description') }}
    </q-card-section>
    <q-form @submit="udpateAuthorEmployment">
      <q-card-section>
        <q-input
          v-model="roleTitle"
          :label="t('orcid-employment-edit.role-title')"
          outlined
          :rules="[(val: string|null) => !!val || t('common.required'),
                   (val: string) => val.length <= 75 || t('common.validation.must-be-less-than-x-characters', [75]),
          ]"
          class="q-mb-md"
        />
        <q-input
          v-model="departmentName"
          :label="t('orcid-employment-edit.department-name')"
          outlined
          :rules="[(val: string|null) => !!val || t('common.required'),
                   (val: string) => val.length <= 75 || t('common.validation.must-be-less-than-x-characters', [75]),
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
        <q-btn color="red" outline :label="$t('common.delete')" icon="mdi-delete-outline" @click="deleteAuthorEmployment" />
        <q-btn v-close-popup :label="$t('common.cancel')" color="primary" outline />
        <q-btn
          type="submit"
          color="primary"
          :label="$t('common.save')"
          :loading="loading"
        />
      </q-card-actions>
    </q-form>
  </BaseDialog>
</template>

<style scoped>

</style>
