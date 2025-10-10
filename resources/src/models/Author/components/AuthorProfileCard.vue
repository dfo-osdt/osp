<script setup lang="ts">
import type { AuthorResource } from '../Author'
import ContentCard from '@/components/ContentCard.vue'
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'
import EditAuthorDialog from './EditAuthorDialog.vue'

const props = defineProps<{
  author: AuthorResource
}>()

const emit = defineEmits<{
  (event: 'updated'): void
}>()

const localeStore = useLocaleStore()
const { t } = useI18n()

const showEditAuthorDialog = ref(false)

const organizationName = computed(() => {
  if (!props.author.data.organization)
    return null
  return localeStore.locale === 'en'
    ? props.author.data.organization.data.name_en
    : props.author.data.organization.data.name_fr
})

const fullName = computed(() => {
  return `${props.author.data.first_name} ${props.author.data.last_name}`
})

const initials = computed(() => {
  const firstName = props.author.data.first_name?.charAt(0).toUpperCase() || ''
  const lastName = props.author.data.last_name?.charAt(0).toUpperCase() || ''
  return `${firstName}${lastName}`
})

function handleAuthorUpdated() {
  showEditAuthorDialog.value = false
  emit('updated')
}
</script>

<template>
  <ContentCard secondary>
    <template #title>
      <q-avatar color="secondary" text-color="white" class="q-mr-sm">
        {{ initials }}
      </q-avatar>
      {{ fullName }}
    </template>
    <template #title-right>
      <q-btn
        v-if="author.can?.update"
        icon="mdi-pencil"
        flat
        round
        dense
        color="secondary"
        @click="showEditAuthorDialog = true"
      >
        <q-tooltip>{{ t('author.edit-author-details') }}</q-tooltip>
      </q-btn>
    </template>

    <div class="q-gutter-md">
      <!-- Email -->
      <div class="flex items-center">
        <q-icon
          name="mdi-email-outline"
          size="sm"
          color="grey-6"
          class="q-mr-sm"
        />
        <a :href="`mailto:${author.data.email}`" class="text-primary">
          {{ author.data.email }}
        </a>
        <CopyToClipboardButton
          :text="author.data.email"
          size="sm"
          class="q-ml-sm"
        />
      </div>

      <!-- ORCID -->
      <div v-if="author.data.orcid" class="flex items-center">
        <OrcidIcon
          :unauthenticated="!author.data.orcid_verified"
          class="q-mr-sm"
          size="sm"
        />
        <a
          :href="author.data.orcid"
          target="_blank"
          rel="noopener noreferrer"
          class="text-primary"
        >
          {{ author.data.orcid.split('/').pop() }}
        </a>
      </div>

      <!-- Organization -->
      <div v-if="author.data.organization" class="flex items-center">
        <q-icon name="mdi-domain" size="sm" color="grey-6" class="q-mr-sm" />
        <div class="column">
          <span class="text-body1">{{ organizationName }}</span>
          <RORLinkSpan
            :organization="author.data.organization"
            class="text-caption text-grey-6"
          />
        </div>
      </div>
    </div>

    <EditAuthorDialog
      v-if="showEditAuthorDialog"
      v-model="showEditAuthorDialog"
      :author="author"
      @updated="handleAuthorUpdated"
    />
  </ContentCard>
</template>

<style scoped></style>
