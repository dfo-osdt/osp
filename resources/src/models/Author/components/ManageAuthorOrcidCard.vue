<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import OrcidAvatar from '@/components/OrcidAvatar.vue'

const localeStore = useLocaleStore()
const authStore = useAuthStore()

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
        $t('manage-author-profile-view.authorize-orcid')
      }}</span>
    </template>
    <q-card-section v-if="isVerified">
      <p>
        {{ $t('orcid.already-verified-text') }}
      </p>
      <div class="text-subtitle1 q-mb-md">
        <span class="text-primary text-weight-bold">ORCID: </span>
        <a
          target="_blank"
          class="text-primary"
          :href="authStore.user.author.data.orcid"
        >{{ authStore.user.author.data.orcid }}</a>
      </div>
      <div class="flex justify-end">
        <q-btn outline color="primary" @click="revokeToken()">
          <OrcidAvatar size="md" /><span class="q-ml-md text-grey-8">{{ $t('ocrid.revoke-btn-text') }}
          </span>
        </q-btn>
      </div>
    </q-card-section>
    <q-card-section v-else>
      <div class="column flex flex-center">
        <div class="q-ma-md text-h5 text-grey-8">
          {{ $t('orcid.verify-header-text') }}
        </div>
        <div>
          <q-btn outline color="primary" :href="url">
            <OrcidAvatar size="lg" /><span
              class="q-ml-md q-my-sm text-grey-8"
            >{{ $t('ocrid.verify-btn-text') }}
            </span>
          </q-btn>
        </div>
      </div>
    </q-card-section>
  </ContentCard>
</template>

<style scoped></style>
