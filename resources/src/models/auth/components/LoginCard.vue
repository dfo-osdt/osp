<template>
    <q-card>
        <q-card-section class="bg-primary text-white">
            <div class="text-h5">{{ t('common.login') }}</div>
        </q-card-section>
        <q-banner v-if="errorMessage" dark class="bg-negative">
            <template #avatar
                ><q-icon name="mdi-alert-circle-outline"
            /></template>
            <div>{{ errorMessage.message }}</div>
        </q-banner>
        <q-card-section class="q-mt-md">
            <q-form class="q-gutter-md" autofocus @submit="login">
                <q-input
                    v-model="email"
                    type="email"
                    filled
                    label="Your email"
                    lazy-rules
                    :rules="[
                        (val) => !!val || 'Email is required',
                        // must be valid email
                        (val) =>
                            /^\S+@\S+\.\S+$/.test(val) || 'Email is invalid',
                    ]"
                    data-cy="email"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="password"
                    type="password"
                    filled
                    label="Your password"
                    :rules="[(val) => !!val || 'Password is required']"
                    data-cy="password"
                    @focus="errorMessage = null"
                />

                <q-toggle v-model="remember" label="Remember me" />

                <div class="flex justify-end">
                    <q-btn
                        label="Login"
                        type="submit"
                        color="primary"
                        data-cy="login"
                        :loading="loading"
                    />
                    <q-btn
                        label="Reset"
                        type="reset"
                        color="primary"
                        flat
                        class="q-ml-sm"
                    />
                </div>
                <div
                    v-if="props.showFooter"
                    class="flex row justify-center q-pt-md"
                >
                    <div>Forgot your password?</div>
                    <div class="q-px-sm">|</div>
                    <div>Don't have an account?</div>
                </div>
            </q-form>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';
import { useQuasar } from 'quasar';

const props = defineProps<{
    showFooter?: boolean;
}>();

const authStore = useAuthStore();
const router = useRouter();
const { t } = useI18n();
const $q = useQuasar();

//user related data
const email = ref((router.currentRoute.value.query?.email as string) || '');
const password = ref('');
const remember = ref(false);

// UI related data
const loading = ref(false);
const errorMessage: Ref<ErrorResponse | null> = ref(null);

async function login() {
    loading.value = true;
    await authStore
        .login(email.value, password.value, remember.value)
        .then(() => {
            router.currentRoute.value.query?.redirect
                ? router.push(
                      router.currentRoute.value.query.redirect as string
                  )
                : router.push({ name: 'dashboard' });
        })
        .catch((err) => {
            errorMessage.value = extractErrorMessages(err);
        });
    loading.value = false;
}

onMounted(async () => {
    /** Redirect if user is authenticated, likely got here following an email link */
    if (authStore.isAuthenticated) {
        // is there a redirect query param?
        if (router.currentRoute.value.query?.redirect) {
            router.push(router.currentRoute.value.query.redirect as string);
        } else {
            $q.notify({
                message: t('auth.redirected'),
                type: 'info',
                icon: 'mdi-information-outline',
            });
            router.push({ name: 'dashboard' });
        }
    }
});
</script>

<style scoped></style>
