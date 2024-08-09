<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import MainPageLayout from '@/layouts/MainPageLayout.vue'
</script>

<template>
  <MainPageLayout :title="$t('common.settings')">
    <div class="row q-gutter-lg q-col-gutter-lg flex">
      <div class="cols-2">
        <ContentCard secondary>
          <template #title>
            {{ $t('common.settings') }}
          </template>
          <q-list>
            <q-item clickable to="/settings/profile">
              <q-item-section avatar>
                <q-icon name="mdi-account-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('SettingsPage.user-profile')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable to="/settings/author">
              <q-item-section avatar>
                <q-icon name="mdi-badge-account-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('SettingsPage.author-profile')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable to="/settings/security">
              <q-item-section avatar>
                <q-icon name="mdi-shield-lock-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('SettingsPage.account-security')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
            <q-item clickable to="/settings/invitations">
              <q-item-section avatar>
                <q-icon name="mdi-email-outline" />
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  {{
                    $t('SentInvitationsCard.title')
                  }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </ContentCard>
      </div>
      <div class="col q-pr-lg">
        <router-view />
      </div>
    </div>
  </MainPageLayout>
</template>

<style scoped></style>
