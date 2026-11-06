<script setup lang="ts">
import type { OrcidReviewContributionResourceList, OrcidReviewStatus } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import TitleText from '@/components/TitleText.vue'
import { OrcidReviewContributionService } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { useReviewContributorRoleOptions } from '@/models/ManuscriptReviewContributor/reviewContributorRoleOptions'

const { t } = useI18n()
const q = useQuasar()
const authStore = useAuthStore()
const localeStore = useLocaleStore()
const { roleLabel } = useReviewContributorRoleOptions()

// a plain link, not an API call: pass the locale so ORCID shows the same language
const connectUrl = computed(() => `/api/user/orcid/verify?locale=${localeStore.locale}`)

const hasAuthor = computed(() => !!authStore.user?.author?.data)

const contributions = ref<OrcidReviewContributionResourceList | null>(null)
const loading = ref(false)
const adding = ref<number | null>(null)

function statusLabel(status: OrcidReviewStatus): string {
  // pending contributions wait for the author to connect ORCID with the write scope
  if (status === 'pending' && !contributions.value?.meta.can_write_to_orcid)
    return t('orcid-review-credit.status.waiting_for_connection')

  const labels: Record<OrcidReviewStatus, string> = {
    not_on_orcid: t('orcid-review-credit.status.not_on_orcid'),
    pending: t('orcid-review-credit.status.pending'),
    on_orcid: t('orcid-review-credit.status.on_orcid'),
  }
  return labels[status]
}

async function addToOrcid(id: number) {
  adding.value = id
  try {
    const added = await OrcidReviewContributionService.add(id)
    if (!contributions.value)
      return
    const index = contributions.value.data.findIndex(contribution => contribution.data.id === id)
    if (index !== -1)
      contributions.value.data[index] = added
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-review-credit.add-error'),
    })
  }
  finally {
    adding.value = null
  }
}

async function loadContributions() {
  loading.value = true
  try {
    contributions.value = await OrcidReviewContributionService.list()
  }
  catch {
    q.notify({
      type: 'negative',
      message: t('orcid-review-credit.load-error'),
    })
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
          <span class="q-ml-sm">{{ t('orcid.verify-btn-text') }}</span>
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
          <q-btn
            v-if="contribution.data.orcid_status === 'not_on_orcid'"
            outline
            no-caps
            color="primary"
            icon="mdi-plus"
            :label="t('orcid-review-credit.add')"
            :loading="adding === contribution.data.id"
            data-test="orcid-review-add"
            @click="addToOrcid(contribution.data.id)"
          />
          <q-badge
            v-else
            outline
            :color="contribution.data.orcid_status === 'on_orcid' ? 'positive' : 'grey-8'"
            :label="statusLabel(contribution.data.orcid_status)"
          />
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
