<script setup lang="ts">
import type { FundingSourceResource } from '../FundingSource'
import { useQuasar } from 'quasar'
import EditFundingSourceDialog from './EditFundingSourceDialog.vue'

const props = defineProps<{
  readonly?: boolean
  fundingSource: FundingSourceResource
}>()
const emit = defineEmits<{
  (event: 'editedFundingSource', fundingSource: FundingSourceResource): void
  (event: 'deleteFundingSource', fundingSource: FundingSourceResource): void
}>()
const $q = useQuasar()
const { t } = useI18n()

async function deleteFundingSource() {
  $q.dialog({
    title: t('fundingSource.delete'),
    message: t('fundingSource.deleteConfirmation', {
      source: props.fundingSource.data.title,
    }),
    cancel: true,
    persistent: true,
  }).onOk(() => {
    emit('deleteFundingSource', props.fundingSource)
  })
}

// edit dialog
const showEditDialog = ref(false)

async function editedFundingSource(fundingSource: FundingSourceResource) {
  showEditDialog.value = false
  emit('editedFundingSource', fundingSource)
}

// locale
const localeStore = useLocaleStore()
const fundingSourceName = computed(() => {
  const { name_en, name_fr } = props.fundingSource.data.funder?.data ?? {
    name_en: 'Undefined',
    name_fr: 'Indéfini',
  }
  if (localeStore.locale === 'fr')
    return name_fr
  return name_en
})
</script>

<template>
  <q-item>
    <q-item-section>
      <q-item-label overline>
        {{ fundingSourceName }}
      </q-item-label>
      <q-item-label>{{ fundingSource.data.title }} </q-item-label>
      <q-item-label caption>
        {{ fundingSource.data.description }}
      </q-item-label>
    </q-item-section>
    <q-item-section v-if="!readonly" side>
      <div class="text-grey-8 q-gutter-xs">
        <q-btn
          class="gt-xs"
          size="12px"
          flat
          dense
          round
          icon="mdi-pencil"
          color="primary"
          @click="showEditDialog = true"
        >
          <q-tooltip>{{ $t('common.edit') }}</q-tooltip>
        </q-btn>
        <q-btn
          class="gt-xs"
          size="12px"
          flat
          dense
          round
          icon="mdi-delete"
          color="negative"
          @click="deleteFundingSource"
        >
          <q-tooltip>{{ $t('common.delete') }}</q-tooltip>
        </q-btn>
      </div>
    </q-item-section>
    <EditFundingSourceDialog
      v-if="showEditDialog"
      v-model="showEditDialog"
      :funding-source="fundingSource.data"
      @edited-funding-source="editedFundingSource"
    />
  </q-item>
</template>

<style scoped></style>
