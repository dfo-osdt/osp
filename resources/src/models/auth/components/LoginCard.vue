<script setup lang="ts">
import type { Ref } from 'vue'
import { useQuasar } from 'quasar'
import type { ErrorResponse } from '@/api/errors'
import { extractErrorMessages } from '@/api/errors'
import PasswordWithToggleInput from '@/components/PasswordWithToggleInput.vue'

const authStore = useAuthStore()
const localeStore = useLocaleStore()
const router = useRouter()
const { t } = useI18n()
const $q = useQuasar()

// user related data
const email = ref((router.currentRoute.value.query?.email as string) || '')
const password = ref('')
const remember = ref(false)

// UI related data
const loading = ref(false)
const errorMessage: Ref<ErrorResponse | null> = ref(null)

async function login() {
  loading.value = true
  await authStore
    .login(email.value, password.value, remember.value)
    .then(() => {
      if (authStore.user?.new_password_required) {
        router.push({ name: 'settings.security' })
        setTimeout(() => {
          $q.notify({
            message: t('login-card.new-password-required'),
            type: 'info',
            icon: 'mdi-information-outline',
          })
        }, 500)
        return
      }
      if (router.currentRoute.value.query?.redirect) {
        router.push(router.currentRoute.value.query.redirect as string)
      }
      else {
        router.push({ name: 'dashboard' })
      }
      notifyUserLastLogin()
    })
    .catch((err) => {
      errorMessage.value = extractErrorMessages(err)
      setTimeout(() => {
        errorMessage.value = null
      }, 10000)
    })
  loading.value = false
}

// verified email section
const verified = ref(
  (router.currentRoute.value.query?.verified as string) || '',
)

onMounted(async () => {
  /** Redirect if user is authenticated, likely got here following an email link */
  if (authStore.isAuthenticated) {
    // is there a redirect query param?
    if (router.currentRoute.value.query?.redirect) {
      router.push(router.currentRoute.value.query.redirect as string)
    }
    else {
      $q.notify({
        message: t('auth.redirected'),
        type: 'info',
        icon: 'mdi-information-outline',
      })
      router.push({ name: 'dashboard' })
    }
  }
  // show verified email message as a banner
  if (verified.value === '1') {
    $q.notify({
      message: t('login-card.email-verified-text'),
      type: 'info',
      icon: 'mdi-information-outline',
    })
  }
})

const showVerified = computed(() => {
  return verified.value === '1'
})

// rules
const emailRules = computed(() => [
  (val: string) => !!val || t('common.validation.email-required'),
  // must be valid email
  (val: string) =>
    /^\S[^\s@]*@\S[^\s.]*\.\S+$/.test(val) || t('common.validation.email-invalid'),
])

const passwordRules = computed(() => [
  (val: string) => !!val || t('common.validation.password-required'),
])

function notifyUserLastLogin() {
  const lastLoginAt = authStore.user?.lastLoginAt
  if (!lastLoginAt) {
    setTimeout(() => {
      $q.notify({
        message: t('auth.last-login-never'),
        color: 'primary',
      })
    }, 500)
    return
  }

  const date = new Date(lastLoginAt).toLocaleString(`${localeStore.locale}-CA`)
  const timeAgo = useLocaleTimeAgo(lastLoginAt)

  const msg = t('auth.last-login', {
    date,
  })

  setTimeout(() => {
    $q.notify({
      message: msg,
      caption: timeAgo.value,
      timeout: 15000,
      color: 'primary',
      progress: true,
      actions: [
        {
          label: t('common.review'),
          color: 'white',
          handler: () => {
            router.push({ name: 'settings.security' })
          },
        },
      ],
    })
  }, 500)
}
</script>

<template>
  <q-card>
    <q-card-section class="bg-primary text-white">
      <div class="text-h5">
        {{ t('common.login') }}
      </div>
    </q-card-section>
    <q-banner v-if="errorMessage" dark class="bg-negative">
      <template #avatar>
        <q-icon name="mdi-alert-circle-outline" />
      </template>
      <div>{{ errorMessage.message }}</div>
    </q-banner>
    <q-banner v-if="showVerified" dark class="bg-secondary">
      <template #avatar>
        <q-icon name="mdi-email-seal-outline" />
      </template>
      <div>{{ t('login-card.email-verified-text') }}</div>
    </q-banner>
    <q-card-section class="q-mt-md">
      <q-form class="q-gutter-md" autofocus @submit="login">
        <q-input
          v-model="email"
          type="email"
          filled
          :label="t('common.your-email')"
          lazy-rules
          :rules="emailRules"
          data-cy="email"
          @focus="errorMessage = null"
        />
        <PasswordWithToggleInput
          v-model="password"
          filled
          :label="t('common.your-password')"
          :rules="passwordRules"
          data-cy="password"
          @focus="errorMessage = null"
        />
        <div class="flex justify-end">
          <q-btn
            :label="t('common.login')"
            type="submit"
            color="primary"
            data-cy="login"
            :loading="loading"
          />
        </div>
        <q-separator class="q-mt-lg" />
        <div class="flex row justify-center">
          <router-link
            :to="{ name: 'forgotPassword' }"
            class="text-grey-8"
          >
            {{
              t('login-card.forgot-your-password')
            }}
          </router-link>
          <div class="q-px-sm">
            |
          </div>
          <router-link
            :to="{ name: 'register' }"
            class="text-grey-8"
          >
            {{
              t('login-card.dont-have-an-account')
            }}
          </router-link>
        </div>
      </q-form>
    </q-card-section>
  </q-card>
</template>

<style scoped></style>
