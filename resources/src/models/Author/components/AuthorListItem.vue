<script setup lang="ts">
import type { AuthorResource } from '../Author'
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import RORLinkSpan from '@/models/Organization/components/RORLinkSpan.vue'

const props = defineProps<{
  author: AuthorResource
}>()

const localeStore = useLocaleStore()

const organizationName = computed(() => {
  return localeStore.locale === 'en' ? props.author.data.organization?.data.name_en : props.author.data.organization?.data.name_fr
})
</script>

<template>
  <q-item>
    <q-item-section>
      <q-item-label
        class="text-body1 text-weight-medium text-primary"
      >
        {{ author.data.last_name }}, {{ author.data.first_name }}
      </q-item-label>
      <q-item-label>
        <template v-if="author.data.organization">
          <span>
            {{ organizationName }}
          </span>
        </template>
      </q-item-label>
      <q-item-label caption>
        <RORLinkSpan :organization="author.data.organization" />
      </q-item-label>
    </q-item-section>
    <q-item-section side top>
      <span>
        <q-icon name="mdi-at" size="16px" color="primary" />
        <a :href="`mailto:${author.data.email}`">{{ author.data.email }}</a>
        <CopyToClipboardButton :text="author.data.email" size="sm" class="q-ml-sm q-pr-none" />
      </span>
      <span v-if="author.data.orcid" class="q-mt-sm">
        <OrcidIcon :unauthenticated="!author.data.orcid_verified" />
        <a
          :href="author.data.orcid"
          target="_blank"
          rel="noopener noreferrer"
          class="q-ml-sm"
        >
          {{ author.data.orcid }}
        </a>
      </span>
    </q-item-section>
  </q-item>
</template>

<style scoped>

</style>
