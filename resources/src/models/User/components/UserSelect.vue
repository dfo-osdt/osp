<script setup lang="ts">
import { QSelect } from 'quasar'
import type { UserResource, UserResourceList } from '../User'
import { UserService } from '../User'
import InviteUserDialog from './InviteUserDialog.vue'

const props = withDefaults(
  defineProps<{
    modelValue: number | null
    disabledEmails?: string[]
    disabledIds?: number[]
    readonly?: boolean
    required?: boolean
  }>(),
  {
    disabledEmails: () => [],
    disabledIds: () => [],
    readonly: false,
    required: false,
  },
)

const emit = defineEmits(['update:modelValue'])

const userSelect = ref<QSelect | null>(null)

const users = ref<UserResourceList>({ data: [] })
const selectedUser = ref<UserResource | null>(null)
const lastSearchTerm = ref('')
const usersLoading = ref(false)
const showInviteUserDialog = ref(false)

watch(selectedUser, (user) => {
  if (user) {
    emit('update:modelValue', user.data.id)
  }
  else {
    // emit('update:modelValue', null);
  }
})

onMounted(async () => {
  if (props.modelValue) {
    const user = await UserService.get(props.modelValue)
    selectedUser.value = user
  }
})

async function filterUsers(val: string, update, abort) {
  lastSearchTerm.value = val
  update(async () => {
    if (val !== '') {
      const needle = val.toLowerCase()
      usersLoading.value = true
      await UserService.list(`limit=10&filter[search]=${needle}`).then(
        (response) => {
          users.value = response
        },
      )
      usersLoading.value = false
    }
  })
}

const { t } = useI18n()
const rules = computed(() => {
  const rules = [
    // required
    (val: string | null) => {
      console.log('required', val)
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

function createdUser(item: UserResource) {
  userSelect.value?.updateInputValue('', true)
  selectedUser.value = item
  showInviteUserDialog.value = false
}

function optionValue(item: UserResource) {
  return item.data.id
}
function optionLabel(item: UserResource) {
  const { first_name, last_name, email } = item.data
  return `${first_name} ${last_name} (${email})`
}
function optionDisable(item: UserResource) {
  return (
    props.disabledEmails.includes(item.data.email)
    || props.disabledIds.includes(item.data.id)
  )
}
</script>

<template>
  <QSelect
    ref="userSelect"
    v-model="selectedUser"
    :options="users.data"
    :option-value="optionValue"
    :option-disable="optionDisable"
    :option-label="optionLabel"
    clearable
    :label="$t('common.user')"
    :loading="usersLoading"
    use-input
    stack-label
    outlined
    :readonly="props.readonly"
    :rules="rules"
    :hide-hint="props.readonly"
    hint="Start typing to search for an user (first name, last name, or email)"
    @filter="filterUsers"
    @clear="selectedUser = null"
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
      <q-separator />
      <q-item clickable @click="showInviteUserDialog = true">
        <q-item-section>
          {{ $t('user-select.cant-find-the-user-youre-looking-for') }}
        </q-item-section>
        <q-item-section side>
          <q-btn
            icon="mdi-plus"
            color="primary"
            size="sm"
            round
            @click="showInviteUserDialog = true"
          >
            <q-tooltip class="text-body2">
              {{
                $t('user-select.invite-a-new-user')
              }}
            </q-tooltip>
          </q-btn>
        </q-item-section>
      </q-item>
    </template>
    <template #option="scope">
      <q-item v-bind="scope.itemProps">
        <q-item-section>
          <q-item-label>
            {{ scope.opt.data.first_name }}
            {{ scope.opt.data.last_name }}
          </q-item-label>
          <q-item-label caption>
            {{ scope.opt.data.email }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
    <template #after-options>
      <q-separator />
      <q-item clickable @click="showInviteUserDialog = true">
        <q-item-section>
          {{ $t('user-select.not-the-user-youre-looking-for') }}
        </q-item-section>
        <q-item-section side>
          <q-btn
            icon="mdi-plus"
            color="primary"
            size="sm"
            round
            @click="showInviteUserDialog = true"
          >
            <q-tooltip class="text-body2">
              {{
                $t('user-select.invite-a-new-user')
              }}
            </q-tooltip>
          </q-btn>
        </q-item-section>
      </q-item>
    </template>
    <InviteUserDialog
      v-if="showInviteUserDialog"
      v-model="showInviteUserDialog"
      @created="createdUser"
    />
  </QSelect>
</template>

<style scoped></style>
