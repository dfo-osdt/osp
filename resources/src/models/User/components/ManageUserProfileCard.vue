<script setup lang="ts">
import type { Ref } from 'vue'
import { useQuasar } from 'quasar'
import type { User } from '../User'
import { UserService } from '../User'
import ContentCard from '@/components/ContentCard.vue'
import LocaleSelect from '@/components/LocaleSelect.vue'
import SensitivityLabelChip from '@/components/SensitivityLabelChip.vue'

const props = defineProps<{
  userId: number
}>()
const emit = defineEmits<{
  (event: 'saved'): void
}>()
const $q = useQuasar()
const { t } = useI18n()

const loading = ref(true)
const user: Ref<User | null> = ref(null)

async function getUser() {
  loading.value = true
  user.value = (await UserService.get(props.userId)).data
  loading.value = false
}

onMounted(async () => {
  getUser()
})

async function save() {
  if (!user.value)
    return
  loading.value = true
  const userResource = await UserService.update(
    user.value.id,
    user.value?.first_name,
    user.value?.last_name,
    user.value?.locale,
  )
  user.value = userResource.data
  loading.value = false
  emit('saved')
  // notify
  $q.notify({
    message: t('common.saved'),
    type: 'positive',
    position: 'top',
  })
}
</script>

<template>
  <ContentCard secondary>
    <template #title>
      {{ t('SettingsPage.user-profile') }}
    </template>
    <template #title-right>
      <SensitivityLabelChip sensitivity="Protected A" />
    </template>
    <q-form v-if="user" @submit="save()">
      <div class="row q-col-gutter-md">
        <q-input
          v-model="user.first_name"
          class="col-12 col-md-6"
          :label="t('common.first-name')"
          outlined
          :rules="[(val) => val.length > 0 || t('common.required')]"
        />
        <q-input
          v-model="user.last_name"
          class="col-12 col-md-6"
          :label="t('common.last-name')"
          outlined
          :rules="[(val) => val.length > 0 || t('common.required')]"
        />
        <q-input
          v-model="user.email"
          class="col-12 col-md-6"
          :label="t('common.email')"
          outlined
          :disable="true"
          :hint="t('user-profile.email-hint')"
        />
        <LocaleSelect v-model="user.locale" />
      </div>
      <q-card-actions align="right">
        <q-btn
          color="primary"
          :label="t('common.save')"
          type="submit"
          :loading="loading"
        />
      </q-card-actions>
    </q-form>
  </ContentCard>
</template>

<style scoped></style>
