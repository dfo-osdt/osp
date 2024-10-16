<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'

const localeStore = useLocaleStore()
const authStore = useAuthStore()
const { t } = useI18n()

const url = computed(() => {
  return `/api/user/orcid/verify?locale=${localeStore.locale}`
})

const isVerified = computed(() => {
  return authStore.user?.author.data.orcid_verified
})

async function revokeToken() {
  await authStore.revokeOrcidToken()
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
        <div class="col-12 col-md-auto">
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
                <OrcidAvatar size="md" /><span class="q-ml-md text-grey-8">{{ t('ocrid.revoke-btn-text') }}
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
                    class="q-ml-md q-my-sm text-grey-8"
                  >{{ t('ocrid.verify-btn-text') }}
                  </span>
                </q-btn>
              </div>
            </div>
          </div>
        </div>
      </div>
    </q-card-section>
  </ContentCard>
</template>

<style scoped></style>
