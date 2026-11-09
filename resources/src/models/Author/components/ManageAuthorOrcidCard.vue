<script setup lang="ts">
import { useQuasar } from 'quasar'
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import ManageAuthorEmploymentsCard from '@/models/AuthorEmployment/components/ManageAuthorEmploymentsCard.vue'
import { OrcidReviewContributionService } from '@/models/ManuscriptReviewContributor/OrcidReviewContribution'
import { AuthorService } from '../Author'

const authStore = useAuthStore()
const { t } = useI18n()
const q = useQuasar()

const localeStore = useLocaleStore()

// a plain link, not an API call: pass the locale so ORCID shows the same language
const url = computed(() => `/api/user/orcid/verify?locale=${localeStore.locale}`)

const isVerified = computed(() => {
  return authStore.user?.author.data.orcid_verified
})

const showOrcidRecord = ref(authStore.user?.author.data.show_orcid_record ?? true)
const savingShowOrcidRecord = ref(false)

// follow the stored value when the user is (re)loaded
watch(() => authStore.user?.author.data.show_orcid_record, (show) => {
  if (show !== undefined)
    showOrcidRecord.value = show
})

async function updateShowOrcidRecord(show: boolean) {
  if (!authStore.user)
    return

  savingShowOrcidRecord.value = true
  try {
    await AuthorService.updateShowOrcidRecord(authStore.user.author.data.id, show)
    await authStore.refreshUser(true)
    q.notify({
      type: 'positive',
      message: show
        ? t('orcid.show-record-on-profile-shown')
        : t('orcid.show-record-on-profile-hidden'),
    })
  }
  catch {
    showOrcidRecord.value = !show
    q.notify({
      type: 'negative',
      message: t('orcid.show-record-on-profile-error'),
    })
  }
  finally {
    savingShowOrcidRecord.value = false
  }
}

const availableReviewCount = ref(0)
const reviewAutoSync = ref(false)

onMounted(async () => {
  if (!authStore.user?.author?.data)
    return

  try {
    const contributions = await OrcidReviewContributionService.list()
    // only the contributions not decided on yet: those on ORCID or declined are not available
    availableReviewCount.value = contributions.data
      .filter(contribution => contribution.data.orcid_status === 'not_on_orcid')
      .length
    reviewAutoSync.value = contributions.meta.auto_sync
  }
  catch {
    // the count and auto sync are only hints; leave the badges hidden
  }
})

async function revokeToken() {
  q.dialog({
    title: t('orcid.revoke-dialog-title'),
    message: t('orcid.revoke-dialog-message'),
    ok: {
      label: t('common.yes'),
      color: 'primary',
    },
    cancel: {
      label: t('common.no'),
      outline: true,
    },
  }).onOk(async () => {
    await authStore.revokeOrcidToken()
  })
}
</script>

<template>
  <ContentCard secondary class="q-mt-lg">
    <template #title>
      <q-icon name="mdi-plus-network-outline" size="md" />
      <span class="q-pl-sm">{{
        t('manage-author-profile-view.authorize-orcid')
      }}</span>
    </template>
    <q-card-section>
      <div class="row q-gutter-xl">
        <div class="col-12 col-md-1">
          <span class="text-primary text-subtitle1">{{
            t('orcid.connect-header')
          }}</span>
        </div>
        <div class="col text-body2 q-pt-xs">
          <p>{{ t('orcid.connect-introduction') }}</p>
          <div v-if="isVerified">
            <div class="text-primary">
              {{ t('orcid.connect-linked-text') }}
            </div>
            <OrcidAvatar size="lg" />
            <a
              target="_blank"
              class="text-primary"
              :href="authStore.user?.author.data.orcid"
            >{{ authStore.user?.author.data.orcid }}</a>
            <div class="flex justify-end">
              <q-btn outline no-caps color="negative" @click="revokeToken()">
                <OrcidAvatar size="md" /><span class="q-ml-md">{{ t('orcid.revoke-btn-text') }}
                </span>
              </q-btn>
            </div>
          </div>
          <div v-else>
            <div class="column flex flex-center">
              <div class="q-ma-md text-h6 text-grey-8">
                {{ t('orcid.verify-header-text') }}
              </div>
              <div>
                <q-btn outline color="primary" :href="url">
                  <OrcidAvatar size="lg" />
                  <span class="q-ml-md q-my-sm text-primary">{{ t('orcid.verify-btn-text') }}
                  </span>
                </q-btn>
              </div>
            </div>
          </div>
        </div>
      </div>
      <q-separator class="q-my-md" />
      <div class="row q-gutter-xl">
        <div class="col-12 col-md-1">
          <span class="text-primary text-subtitle1">{{
            t('orcid.settings-title')
          }}</span>
        </div>
        <div class="col text-body2 q-pt-xs">
          <p>{{ t('orcid.settings-section-intro') }}</p>
          <q-list bordered separator class="rounded-borders">
            <q-item>
              <q-item-section avatar top>
                <q-icon name="mdi-account-outline" color="primary" />
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ t('orcid.show-record-on-profile') }}</q-item-label>
                <q-item-label caption>
                  {{ isVerified ? t('orcid.show-record-on-profile-hint') : t('orcid.show-record-on-profile-connect-hint') }}
                </q-item-label>
                <q-item-label v-if="authStore.user">
                  <router-link
                    class="text-primary text-caption"
                    :to="{ name: 'author.profile', params: { id: authStore.user.author.data.id } }"
                  >
                    {{ t('orcid.view-author-page') }}
                  </router-link>
                </q-item-label>
              </q-item-section>
              <q-item-section side top>
                <q-toggle
                  v-model="showOrcidRecord"
                  :aria-label="t('orcid.show-record-on-profile')"
                  :disable="!isVerified || savingShowOrcidRecord"
                  @update:model-value="updateShowOrcidRecord"
                />
              </q-item-section>
            </q-item>
            <q-item
              v-if="authStore.user?.author?.data"
              clickable
              :to="{ name: 'settings.author.orcid_reviews' }"
              data-test="orcid-review-credit-link"
            >
              <q-item-section avatar top>
                <q-icon name="mdi-account-check-outline" color="primary" />
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ t('orcid-review-credit.title') }}</q-item-label>
                <q-item-label caption>
                  {{ t('orcid-review-credit.intro') }}
                </q-item-label>
              </q-item-section>
              <q-item-section side>
                <div class="row items-center no-wrap q-gutter-sm">
                  <q-badge
                    v-if="reviewAutoSync"
                    outline
                    color="positive"
                    data-test="orcid-review-credit-auto-sync"
                  >
                    <q-icon name="mdi-autorenew" class="q-mr-xs" />
                    {{ t('orcid-review-credit.auto-sync-badge') }}
                  </q-badge>
                  <q-badge
                    v-if="availableReviewCount"
                    outline
                    color="primary"
                    :label="t('orcid-review-credit.available-count', { count: availableReviewCount })"
                    data-test="orcid-review-credit-count"
                  />
                  <q-icon name="mdi-chevron-right" />
                </div>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </div>
      <q-separator class="q-my-md" />
      <div class="row q-gutter-xl">
        <div class="col-12 col-md-1">
          <span class="text-primary text-subtitle1">{{
            t('orcid.employment-title')
          }}</span>
        </div>
        <div class="col text-body2 q-pt-xs">
          <p>{{ t('orcid.employment-section-intro') }}</p>
          <ManageAuthorEmploymentsCard
            v-if="authStore.user"
            :author-id="authStore.user?.author.data.id"
            :disabled="!isVerified"
          />
        </div>
      </div>
    </q-card-section>
  </ContentCard>
</template>

<style scoped></style>
