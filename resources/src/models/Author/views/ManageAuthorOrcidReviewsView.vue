<script setup lang="ts">
import type { OrcidReviewContributionResourceList, OrcidReviewStatus } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import TitleText from '@/components/TitleText.vue'
import { OrcidReviewContributionService } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { useReviewContributorRoleOptions } from '@/models/ManuscriptReviewContributor/reviewContributorRoleOptions'

const { t } = useI18n()
const authStore = useAuthStore()
const localeStore = useLocaleStore()
const { roleLabel } = useReviewContributorRoleOptions()

// a plain link, not an API call: pass the locale so ORCID shows the same language
const connectUrl = computed(() => `/api/user/orcid/verify?locale=${localeStore.locale}`)

const hasAuthor = computed(() => !!authStore.user?.author?.data)

function statusLabel(status: OrcidReviewStatus): string {
  const labels: Record<OrcidReviewStatus, string> = {
    not_on_orcid: t('orcid-review-credit.status.not_on_orcid'),
  }
  return labels[status]
}

const contributions = ref<OrcidReviewContributionResourceList | null>(null)
const loading = ref(false)

async function loadContributions() {
  loading.value = true
  try {
    contributions.value = await OrcidReviewContributionService.list()
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  if (hasAuthor.value)
    loadContributions()
})
</script>

<template>
  <q-btn
    v-if="hasAuthor"
    flat
    no-caps
    dense
    color="primary"
    icon="mdi-arrow-left"
    class="q-mb-sm"
    :label="t('orcid-review-credit.back')"
    :to="{ name: 'settings.author' }"
  />
  <ContentCard v-if="hasAuthor" secondary class="q-mb-xl" data-test="orcid-review-credit">
    <template #title>
      <q-icon name="mdi-account-check-outline" size="md" />
      <span class="q-pl-sm">{{ t('orcid-review-credit.title') }}</span>
    </template>
    <template #subtitle>
      {{ t('orcid-review-credit.intro') }}
    </template>
    <q-banner
      v-if="contributions && !contributions.meta.can_write_to_orcid"
      class="bg-blue-grey-1 text-grey-9 q-mb-md"
      rounded
      dense
      inline-actions
      data-test="orcid-connect-banner"
    >
      <template #avatar>
        <q-icon name="mdi-information-outline" color="primary" />
      </template>
      {{ t('orcid-review-credit.connect-banner') }}
      <template #action>
        <q-btn outline no-caps color="primary" :href="connectUrl">
          <OrcidAvatar size="sm" />
          <span class="q-ml-sm">{{ t('ocrid.verify-btn-text') }}</span>
        </q-btn>
      </template>
    </q-banner>
    <q-inner-loading :showing="loading" />
    <q-list v-if="contributions" bordered separator class="rounded-borders">
      <q-item v-for="contribution in contributions.data" :key="contribution.data.id">
        <q-item-section>
          <q-item-label class="text-weight-medium">
            <TitleText :title="contribution.data.publication.title" />
          </q-item-label>
          <q-item-label caption>
            {{ contribution.data.publication.journal.data.title }}
          </q-item-label>
          <q-item-label caption>
            {{ roleLabel(contribution.data.role) }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-badge outline color="grey-8" :label="statusLabel(contribution.data.orcid_status)" />
        </q-item-section>
      </q-item>
      <q-item v-if="contributions.data.length === 0">
        <q-item-section class="text-grey-7">
          {{ t('orcid-review-credit.empty') }}
        </q-item-section>
      </q-item>
    </q-list>
  </ContentCard>
</template>

<style scoped></style>
