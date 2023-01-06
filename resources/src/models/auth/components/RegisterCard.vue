<template>
    <q-card>
        <q-card-section class="bg-primary text-white">
            <div class="text-h5">{{ title }}</div>
        </q-card-section>
        <q-banner v-if="errorMessage" dark class="bg-negative">
            <template #avatar
                ><q-icon name="mdi-alert-circle-outline"
            /></template>
            <div>{{ errorMessage.message }}</div>
        </q-banner>
        <q-card-section v-if="registered === null" class="q-mt-md">
            <q-form class="q-gutter-md" autofocus @submit="register">
                <q-input
                    v-model="first_name"
                    type="text"
                    filled
                    label="Your first name"
                    lazy-rules
                    :rules="[
                        (val) => !!val || 'Name is required',
                        // must be valid name
                        (val) => /^[a-zA-Z ]+$/.test(val) || 'Name is invalid',
                    ]"
                    data-cy="name"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="last_name"
                    type="text"
                    filled
                    label="Your last name"
                    lazy-rules
                    :rules="[
                        (val) => !!val || 'Name is required',
                        // must be valid name
                        (val) => /^[a-zA-Z ]+$/.test(val) || 'Name is invalid',
                    ]"
                    data-cy="name"
                    @focus="errorMessage = null"
                />
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
                <q-input
                    v-model="password_confirmation"
                    type="password"
                    filled
                    label="Confirm password"
                    :rules="[
                        (val) => !!val || 'Password is required',
                        (val) => val === password || 'Passwords do not match',
                    ]"
                    data-cy="password"
                    @focus="errorMessage = null"
                />
                <div class="flex justify-end">
                    <q-btn
                        label="Register"
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
            </q-form>
        </q-card-section>
        <q-card-section v-else>
            <div class="row justify-center">
                <q-img
                    src="/assets/splash_images/undraw_mailbox_re_dvds.svg"
                    alt="Mailbox"
                    width="50%"
                    class="col-auto"
                />
                <div class="text-h5 col-12 text-center">
                    Just one last step...
                </div>
                <div class="text-body1 q-mt-md text-grey-8 col-12">
                    Please check your email ({{ registered.email }}) for a
                    confirmation link. To protect your account, you will not be
                    able to login until you've confirmed your email address.
                </div>
            </div>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';
import { useQuasar } from 'quasar';
import {
    SanctumRegisterResponse,
    SanctumRegisterUser,
    useSanctum,
} from '@/api/sanctum';

const sanctum = useSanctum();

const props = defineProps<{
    showFooter?: boolean;
}>();

const authStore = useAuthStore();
const router = useRouter();
const localeStore = useLocaleStore();
const { t } = useI18n();
const $q = useQuasar();

//user related data
const email = ref((router.currentRoute.value.query?.email as string) || '');
const password = ref('');
const password_confirmation = ref('');
const first_name = ref('');
const last_name = ref('');

// UI related data
const loading = ref(false);
const errorMessage: Ref<ErrorResponse | null> = ref(null);
const registered: Ref<SanctumRegisterResponse | null> = ref(null);
const title = computed(() => {
    return registered.value === null ? 'Register' : 'Registered';
});

async function register() {
    loading.value = true;
    const user: SanctumRegisterUser = {
        first_name: first_name.value,
        last_name: last_name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        locale: localeStore.locale,
    };

    await sanctum
        .register(user)
        .then((response) => {
            if (response.status === 200) {
                registered.value = response.data;
            }
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
