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
                    :label="$t('common.your-first-name')"
                    lazy-rules
                    :rules="nameRules"
                    data-cy="name"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="last_name"
                    type="text"
                    filled
                    :label="$t('common.your-last-name')"
                    lazy-rules
                    :rules="nameRules"
                    data-cy="name"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="email"
                    type="email"
                    filled
                    :label="$t('common.your-email')"
                    lazy-rules
                    :rules="emailRules"
                    data-cy="email"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="password"
                    type="password"
                    filled
                    :label="$t('common.your-password')"
                    :rules="passwordRules"
                    data-cy="password"
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="password_confirmation"
                    type="password"
                    filled
                    :label="$t('common.confirm-password')"
                    :rules="passwordConfirmationRules"
                    data-cy="password"
                    @focus="errorMessage = null"
                />
                <div class="flex justify-end">
                    <q-btn
                        :label="$t('common.register')"
                        type="submit"
                        color="primary"
                        data-cy="login"
                        :loading="loading"
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
                    {{ $t('register-card.registered-subtitle') }}
                </div>
                <div class="text-body1 q-mt-md text-grey-8 col-12">
                    {{
                        $t('register-card.registered.text', [registered.email])
                    }}
                </div>
            </div>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';
import {
    locale,
    SanctumRegisterResponse,
    SanctumRegisterUser,
    useSanctum,
} from '@/api/sanctum';

const sanctum = useSanctum();
const router = useRouter();
const localeStore = useLocaleStore();
const { t } = useI18n();

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
    return registered.value === null
        ? t('common.register')
        : t('common.registered');
});

async function register() {
    loading.value = true;
    const user: SanctumRegisterUser = {
        first_name: first_name.value,
        last_name: last_name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        locale: localeStore.locale as locale,
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
            setTimeout(() => {
                errorMessage.value = null;
            }, 10000);
        });

    loading.value = false;
}

// rules
const nameRules = computed(() => [
    (val: string) => !!val || t('common.validation.name-required'),
    // must be valid name
    (val: string) =>
        /^[a-zA-Z ]+$/.test(val) || t('common.validation.name-invalid'),
]);

const emailRules = computed(() => [
    (val: string) => !!val || t('common.validation.email-required'),
    // must be valid email
    (val: string) =>
        /^\S+@\S+\.\S+$/.test(val) || t('common.validation.email-invalid'),
]);

const passwordRules = computed(() => [
    (val: string) => !!val || t('common.validation.password-required'),
]);

const passwordConfirmationRules = computed(() => [
    (val: string) => !!val || t('common.validation.password-required'),
    (val: string) =>
        val === password.value || t('common.validation.passwords-match'),
]);
</script>

<style scoped></style>