<script setup lang="ts">
import type {
  OrganizationResource,
  OrganizationResourceList,
} from '../Organization'
import { SpatieQuery } from '@/api/SpatieQuery'
import { QSelect } from 'quasar'
import {
  OrganizationService,
} from '../Organization'
import CreateOrganizationDialog from './CreateOrganizationDialog.vue'

const props = defineProps<{
  modelValue: number | null
  initialSearchTerm?: string
  showDefaultOrganization?: boolean
}>()

const emit = defineEmits(['update:modelValue'])

const localeStore = useLocaleStore()
const { copy } = useClipboard()

const organizationSelect = ref<QSelect | null>(null)

const organizations = ref<OrganizationResourceList>({ data: [] })
const selectedOrganization = ref<OrganizationResource | null>(null)
const lastSearchTerm = ref('')
const organizationsLoading = ref(false)
const showCreateOrganizationDialog = ref(false)
const defaultOrganiztionId = Number(import.meta.env.VITE_OSP_DEFAULT_ORG_ID) || 1

watch(() => props.modelValue, (value) => {
  if (value === null) {
    selectedOrganization.value = null
  }
})

watch(selectedOrganization, (organization) => {
  emit('update:modelValue', organization?.data.id || null)
})

onMounted(async () => {
  if (props.modelValue) {
    selectedOrganization.value = await OrganizationService.find(
      props.modelValue,
    )
    return
  }
  if (props.initialSearchTerm) {
    organizationSelect.value?.filter(props.initialSearchTerm)
  }
  if (props.modelValue === null && props.showDefaultOrganization) {
    selectedOrganization.value = await OrganizationService.find(
      defaultOrganiztionId,
    )
  }
})

async function filterOrganizations(val: string, update: (arg: () => Promise<void>) => void) {
  lastSearchTerm.value = val
  update(async () => {
    if (val !== '') {
      const needle = val.toLowerCase()
      organizationsLoading.value = true

      const query = new SpatieQuery()
      query
        .filter('search', needle)
        .sort('name-en-length', 'asc')
        .sort(`name_${localeStore.locale}`, 'asc')
        .paginate(1, 10)

      await OrganizationService.list(query).then((response) => {
        organizations.value = response
      })
      organizationsLoading.value = false
    }
  })
}

function createdOrganization(item: OrganizationResource) {
  organizationSelect.value?.updateInputValue('', true)
  organizations.value.data.push(item)
  selectedOrganization.value = item
  showCreateOrganizationDialog.value = false
}

function optionValue(item: OrganizationResource) {
  return item.data.id
}
function optionLabel(item: OrganizationResource) {
  let label: string

  if (localeStore.locale === 'fr') {
    label = item.data.name_fr
    if (item.data.abbr_fr) {
      label += ` (${item.data.abbr_fr})`
    }
    return label
  }

  // Default to English
  const { name_en, abbr_en } = item.data
  label = name_en
  if (abbr_en) {
    label += ` (${abbr_en})`
  }
  return label
}
</script>

<template>
  <QSelect
    ref="organizationSelect"
    v-model="selectedOrganization"
    :options="organizations.data"
    :option-value="optionValue"
    :option-label="optionLabel"
    :label="$t('common.organization')"
    :loading="organizationsLoading"
    use-input
    clearable
    stack-label
    outlined
    :hint="$t('organization-select.hint')"
    @filter="filterOrganizations"
  >
    <template #no-option>
      <template v-if="lastSearchTerm === ''">
        <q-item>
          <q-item-section class="text-grey">
            {{ $t('organization-select.hint') }}
          </q-item-section>
        </q-item>
      </template>
      <template v-else>
        <q-item>
          <q-item-section class="text-grey">
            {{ $t('common.no-results-found-for') }}
            <strong>{{ lastSearchTerm }}</strong>
          </q-item-section>
        </q-item>
        <q-separator />
        <q-item clickable @click="showCreateOrganizationDialog = true">
          <q-item-section>
            {{ $t('organization-select.cant-find') }}
          </q-item-section>
          <q-item-section side>
            <q-btn
              icon="mdi-plus"
              color="primary"
              size="sm"
              round
              @click="showCreateOrganizationDialog = true"
            >
              <q-tooltip class="text-body2">
                {{
                  $t('organization-select.add-a-new-organization')
                }}
              </q-tooltip>
            </q-btn>
          </q-item-section>
        </q-item>
      </template>
    </template>
    <template #option="{ itemProps, opt }">
      <q-item v-bind="itemProps">
        <q-item-section>
          <q-item-label>
            {{ (opt as OrganizationResource).data[`name_${localeStore.locale}`] }}
          </q-item-label>
          <q-item-label v-if="opt.data.ror_identifier" caption>
            {{ (opt as OrganizationResource).data.ror_identifier }}
            <q-btn
              icon="mdi-content-copy"
              size="xs"
              flat
              dense
              @click="copy(opt.data.ror_identifier)"
            >
              <q-tooltip>{{ $t('common.copy-to-clipboard') }}</q-tooltip>
            </q-btn>
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-item-label>
            <q-icon name="mdi-earth" class="q-mr-xs" />{{ (opt as OrganizationResource).data.country_code }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
    <CreateOrganizationDialog
      v-if="showCreateOrganizationDialog"
      v-model="showCreateOrganizationDialog"
      @created="createdOrganization"
    />
  </QSelect>
</template>
