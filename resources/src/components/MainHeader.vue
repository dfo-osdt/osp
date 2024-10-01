<script setup lang="ts">
const emit = defineEmits(['toggleLeftDrawer'])

const { t } = useI18n()
// stores
const authStore = useAuthStore()
const localeStore = useLocaleStore()

// toggle the left drawer
function toggleLeftDrawer() {
  emit('toggleLeftDrawer')
}
</script>

<template>
  <q-header bordered class="q-py-sm bg-white text-secondary">
    <q-toolbar>
      <q-btn
        v-if="authStore.isAuthenticated"
        class="q-mr-lg q-mt-xs"
        flat
        dense
        round
        :icon="authStore.leftDrawerOpen ? 'mdi-menu-open' : 'mdi-menu'"
        aria-label="t('osp.alt.toggle-drawer')"
        @click="toggleLeftDrawer"
      />
      <router-link to="/">
        <q-img
          src="/assets/logo.svg"
          width="40px"
          :alt="t('osp.alt.logo')"
        />
      </router-link>
      <q-toolbar-title class="q-mt-sm text-black">
        {{
          t('common.app-name')
        }}
      </q-toolbar-title>
      <!-- login button -->
      <q-btn
        v-if="!authStore.isAuthenticated"
        outline
        dense
        icon="mdi-login"
        :label="t('common.login')"
        padding="xs md"
        @click="$router.push({ name: 'login' })"
      />
      <!-- drop down menu with logout, profile, and dashboard -->
      <q-btn
        v-if="authStore.isAuthenticated"
        round
        color="secondary"
        :label="authStore.user?.initials"
      >
        <q-menu :offset="[0, 10]">
          <q-list>
            <q-item-label header>
              {{
                authStore.user?.fullName
              }}
            </q-item-label>
            <q-separator />
            <q-item v-ripple to="/settings" clickable>
              <q-item-section>
                <q-item-label>
                  {{
                    t('common.settings')
                  }}
                </q-item-label>
              </q-item-section>
              <q-item-section avatar>
                <q-icon name="mdi-account-cog-outline" />
              </q-item-section>
            </q-item>
            <q-item v-ripple to="/dashboard" clickable>
              <q-item-section>
                <q-item-label>
                  {{
                    t('common.dashboard')
                  }}
                </q-item-label>
              </q-item-section>
              <q-item-section avatar>
                <q-icon name="mdi-view-dashboard-outline" />
              </q-item-section>
            </q-item>
            <q-item v-ripple to="/auth/logout" clickable>
              <q-item-section>
                <q-item-label>
                  {{
                    t('common.logout')
                  }}
                </q-item-label>
              </q-item-section>
              <q-item-section avatar>
                <q-icon name="mdi-logout" />
              </q-item-section>
            </q-item>
          </q-list>
        </q-menu>
      </q-btn>
      <q-btn
        :label="localeStore.otherLocale"
        flat
        round
        class="q-mx-xs"
        @click="localeStore.toggleLocale()"
      />
    </q-toolbar>
  </q-header>
</template>

<style scoped></style>
