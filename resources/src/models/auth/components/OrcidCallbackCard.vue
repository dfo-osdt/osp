<script setup lang="ts">
import type { Author } from '@/models/Author/Author'
import { AuthorService } from '@/models/Author/Author'

const router = useRouter()
const authStore = useAuthStore()
const { t } = useI18n()

type Statuses = 'success' | 'invalid_key' | 'invalid_user' | 'failed' | 'error' | undefined
type OrcidErrorType = 'access_denied' | undefined
const status = ref((router.currentRoute.value.query?.status as Statuses) || undefined)
const orcidErrorType = ref((router.currentRoute.value.query?.error as OrcidErrorType) || undefined)
const orcidErrorMessage = ref(router.currentRoute.value.query?.error_description as string | undefined)

const loading = ref(true)
const valid = computed(() => status.value === 'success')

const errorMessage = computed(() => {
  switch (status.value) {
    case 'invalid_key':
      return t('orcid.invalid-key')
    case 'invalid_user':
      return t('orcid.invalid-user')
    case 'error':
      return `${t('orcid.error')}: ${orcidErrorMessage.value}`
    case 'failed':
    case undefined:
    default:
      return t('orcid.failed')
  }
})

const author = ref<Author | null>(null)

onMounted(async () => {
  if (status.value === 'success') {
    if (!authStore.user?.authorId) {
      status.value = undefined
      return
    }
    const { data } = await AuthorService.find(authStore.user.authorId)
    author.value = data
  }
  loading.value = false
})
</script>

<template>
  <q-card bordered flat class="bg-teal-1">
    <q-card-section class="flex flex-center column">
      <template v-if="loading">
        <h5>{{ $t('orcid.verifying') }}</h5>
        <q-spinner-dots size="50px" color="primary" />
      </template>
      <template v-else-if="valid">
        <q-icon
          name="mdi-check-decagram-outline"
          size="50px"
          color="primary"
          class="q-mb-md"
        />
        <h5 class="q-my-none text-center">
          {{ $t('orcid.verified-with') }}
        </h5>

        <h6 class="q-mt-md">
          {{ author?.orcid }}
        </h6>
      </template>
      <template v-else>
        <q-icon name="error" size="50px" color="red" />
        <h5 class="q-mb-sm">
          {{ t('orcid.error-verifying') }}
        </h5>
        <p>{{ errorMessage }}</p>
      </template>
      <q-btn
        class="q-mt-md"
        label="Continue"
        color="primary"
        @click="
          router.push({
            name: 'settings.author',
          })
        "
      />
    </q-card-section>
  </q-card>
</template>

<style scoped></style>
