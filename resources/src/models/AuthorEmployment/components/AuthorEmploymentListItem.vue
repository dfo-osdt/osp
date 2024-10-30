<script setup lang="ts">
import type { AuthorEmploymentResource } from '../AuthorEmployement'
import EditAuthorEmploymentDialog from './EditAuthorEmploymentDialog.vue'
import SyncStatusIcon from './SyncStatusIcon.vue'

const props = defineProps<{
  authorEmployment: AuthorEmploymentResource
}>()

const emit = defineEmits<{
  changed: []
}>()

const localeStore = useLocaleStore()
const { t } = useI18n()

const organization = computed(() => {
  if (props.authorEmployment.data.organization) {
    return localeStore.locale === 'en'
      ? props.authorEmployment.data.organization.data.name_en
      : props.authorEmployment.data.organization.data.name_fr
  }
  return '-'
})

const startDate = computed(() => {
  return useLocaleDate(props.authorEmployment.data.start_date)
})

const endDate = computed(() => {
  if (props.authorEmployment.data.end_date === null) {
    return t('common.present')
  }
  return useLocaleDate(props.authorEmployment.data.end_date)
})

const canEdit = computed(() => {
  return props.authorEmployment.can?.update ?? false
})

const showEditDialog = ref(false)

function changed() {
  showEditDialog.value = false
  emit('changed')
}
</script>

<template>
  <q-item :clickable="canEdit" @click="showEditDialog = true">
    <q-item-section>
      <q-item-label overline>
        {{ organization }}
      </q-item-label>
      <q-item-label caption>
        {{ authorEmployment.data.role_title }}, {{ authorEmployment.data.department_name }}
      </q-item-label>
      <q-item-label>
        {{ startDate }} - {{ endDate }}
      </q-item-label>
    </q-item-section>
    <q-item-section side top>
      <SyncStatusIcon :author-employment="authorEmployment" />
    </q-item-section>
    <EditAuthorEmploymentDialog
      v-if="showEditDialog"
      v-model="showEditDialog"
      :author-employment="authorEmployment"
      @changed="changed"
      @deleted="changed"
    />
  </q-item>
</template>

<style scoped>

</style>
