<script setup lang="ts">
import type { AzureDirectoryUserResource, AzureDirectoryUserResourceList } from '../AzureDirectoryUser'
import { QSelect } from 'quasar'
import { AzureDirectoryUserService } from '../AzureDirectoryUser'

const props = withDefaults(
  defineProps<{
    readonly?: boolean
    required?: boolean
  }>(),
  {
    readonly: false,
    required: false,
  },
)
const modelValue = defineModel<AzureDirectoryUserResource | null>()
const userSelect = ref<QSelect | null>(null)

const users = ref<AzureDirectoryUserResourceList>({ data: [] })
const lastSearchTerm = ref('')
const usersLoading = ref(false)

const search = useDebounceFn(async (val: string) => {
  if (val !== '' && val.length >= 3) {
    const needle = val.toLowerCase()
    usersLoading.value = true
    await AzureDirectoryUserService.search(needle).then(
      (response) => {
        users.value = response
      },
    )
    usersLoading.value = false
  }
}, 200)

async function filterUsers(val: string, update: any) {
  lastSearchTerm.value = val
  update(async () => {
    if (val === '') {
      users.value = { data: [] }
      return
    }
    search(val)
  })
}

const { t } = useI18n()
const rules = computed(() => {
  const rules = [
    // required
    (val: string | null) => {
      if (!props.required)
        return true
      const msg = t('common.required')
      if (val === null)
        return msg
      return true
    },
  ]
  return rules
})

function optionLabel(item: AzureDirectoryUserResource) {
  const { first_name, last_name, email } = item.data
  return `${first_name} ${last_name} (${email})`
}
</script>

<template>
  <QSelect
    ref="userSelect"
    v-model="modelValue"
    :options="users.data"
    :option-label="optionLabel"
    clearable
    :label="$t('common.email')"
    :loading="usersLoading"
    use-input
    stack-label
    outlined
    :readonly="props.readonly"
    :rules="rules"
    :hide-hint="props.readonly"
    :hint="t('azure-user-select.search-hint')"
    @filter="filterUsers"
    @clear="modelValue = null"
  >
    <template #no-option>
      <q-item>
        <q-item-section class="text-grey">
          <template v-if="lastSearchTerm === ''">
            {{
              $t('user-select.start-typing-to-search-for-a-user')
            }}
          </template>
          <template v-else>
            {{ $t('common.no-results-found-for') }}
            <strong>{{ lastSearchTerm }}</strong>
          </template>
        </q-item-section>
      </q-item>
    </template>
    <template #option="scope">
      <q-item v-bind="scope.itemProps">
        <q-item-section>
          <q-item-label>
            {{ scope.opt.data.display_name }}
          </q-item-label>
          <q-item-label caption class="text-primary">
            {{ scope.opt.data.job_title }}
          </q-item-label>
          <q-item-label caption>
            {{ scope.opt.data.email }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
  </QSelect>
</template>

<style scoped></style>
