<script setup lang="ts">
import type { AuthorOrcidRecord, AuthorResource } from '../Author'
import ContentCard from '@/components/ContentCard.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import { AuthorService } from '../Author'

const props = defineProps<{
  author: AuthorResource
}>()

const { t } = useI18n()
const authStore = useAuthStore()

const orcidRecord = ref<AuthorOrcidRecord | null>(null)
const loading = ref(false)
const expanded = ref(false)
const isClamped = ref(false)
const biographyElement = ref<HTMLElement | null>(null)

const isOwnProfile = computed(() => authStore.user?.author.data.id === props.author.data.id)
const shouldLoad = computed(() => props.author.data.orcid_connected && props.author.data.show_orcid_record)
const biography = computed(() => orcidRecord.value?.record?.biography ?? null)
const isUnavailable = computed(() => orcidRecord.value?.status === 'unavailable')

// the author chose to show their record but is not (or no longer) connected to ORCID
const showConnectPrompt = computed(() => isOwnProfile.value
  && props.author.data.show_orcid_record
  && !loading.value
  && (!props.author.data.orcid_connected || orcidRecord.value?.status === 'empty'))

const showNothingPublicHint = computed(() => isOwnProfile.value
  && orcidRecord.value?.status === 'available'
  && !biography.value)

const isVisible = computed(() => loading.value
  || isUnavailable.value
  || biography.value !== null
  || showConnectPrompt.value
  || showNothingPublicHint.value)

async function loadOrcidRecord() {
  orcidRecord.value = null
  expanded.value = false
  if (!shouldLoad.value)
    return

  loading.value = true
  try {
    orcidRecord.value = await AuthorService.getOrcidRecord(props.author.data.id)
  }
  catch {
    orcidRecord.value = { status: 'unavailable', record: null }
  }
  finally {
    loading.value = false
  }
}

// only offer "Read more" when the biography is longer than the clamped lines
function checkClamped() {
  const element = biographyElement.value
  isClamped.value = element !== null && element.scrollHeight > element.clientHeight
}

useResizeObserver(biographyElement, () => {
  if (!expanded.value)
    checkClamped()
})

onMounted(loadOrcidRecord)
watch(() => [props.author.data.id, shouldLoad.value], loadOrcidRecord)
</script>

<template>
  <ContentCard v-if="isVisible" secondary>
    <template #title>
      <OrcidIcon size="sm" class="q-mr-sm" />
      {{ t('author-profile.orcid-biography') }}
    </template>

    <template v-if="loading">
      <q-skeleton type="text" />
      <q-skeleton type="text" />
      <q-skeleton type="text" width="60%" />
    </template>

    <div v-else-if="isUnavailable" class="text-grey-7">
      {{ t('author-profile.orcid-unavailable') }}
    </div>

    <template v-else-if="biography">
      <!-- plain text from ORCID: interpolated (escaped), never rendered as HTML -->
      <div
        ref="biographyElement"
        class="text-body2 orcid-biography"
        :class="{ 'orcid-biography--clamped': !expanded }"
      >
        {{ biography }}
      </div>
      <q-btn
        v-if="isClamped || expanded"
        flat
        dense
        no-caps
        color="primary"
        class="q-mt-sm"
        :label="expanded ? t('author-profile.read-less') : t('author-profile.read-more')"
        @click="expanded = !expanded"
      />
      <div class="text-caption text-grey-7 q-mt-sm">
        {{ t('author-profile.orcid-source') }}
        <a
          :href="author.data.orcid"
          target="_blank"
          rel="noopener noreferrer"
          class="text-primary"
        >{{ author.data.orcid }}<q-icon name="mdi-open-in-new" class="q-ml-xs" /></a>
      </div>
    </template>

    <div v-else-if="showConnectPrompt" class="text-grey-7">
      <p>{{ t('author-profile.orcid-connect-prompt') }}</p>
      <q-btn
        outline
        no-caps
        color="primary"
        :to="{ name: 'settings.author' }"
        :label="t('author-profile.orcid-connect-action')"
      />
    </div>

    <div v-else-if="showNothingPublicHint" class="text-grey-7">
      {{ t('author-profile.orcid-nothing-public') }}
    </div>
  </ContentCard>
</template>

<style scoped>
.orcid-biography {
  white-space: pre-line;
  overflow-wrap: anywhere;
}

.orcid-biography--clamped {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 6;
  line-clamp: 6;
  overflow: hidden;
}
</style>
