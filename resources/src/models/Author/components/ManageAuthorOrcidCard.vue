<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'
import ManageAuthorEmploymentsCard from '@/models/AuthorEmployment/components/ManageAuthorEmploymentsCard.vue'
import { useQuasar } from 'quasar'

const localeStore = useLocaleStore()
const authStore = useAuthStore()
const { t } = useI18n()
const q = useQuasar()

const url = computed(() => {
  return `/api/user/orcid/verify?locale=${localeStore.locale}`
})

const isVerified = computed(() => {
  return authStore.user?.author.data.orcid_verified
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
          <span class="text-primary text-subtitle1">{{ t('orcid.connect-header') }}</span>
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
              <q-btn outline color="primary" @click="revokeToken()">
                <OrcidAvatar size="md" /><span class="q-ml-md text-primary">{{ t('ocrid.revoke-btn-text') }}
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
                  <span
                    class="q-ml-md q-my-sm text-primary"
                  >{{ t('ocrid.verify-btn-text') }}
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
          <span class="text-primary text-subtitle1">{{ t('orcid.employment-title') }}</span>
        </div>
        <div class="col text-body2 q-pt-xs">
          <p>{{ t('orcid.employment-section-intro') }}</p>
          <ManageAuthorEmploymentsCard v-if="authStore.user" :author-id="authStore.user?.author.data.id" :disabled="!isVerified" />
        </div>
      </div>
    </q-card-section>
  </ContentCard>
</template>

<style scoped></style>
