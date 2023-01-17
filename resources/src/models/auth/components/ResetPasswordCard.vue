<template>
    <q-card v-if="canReset" flat>
        <div class="flex justify-around q-mb-none">
            <q-icon name="mdi-lock-reset" color="primary" size="4rem" />
        </div>
        <div class="text-primary">
            <div class="text-h5 text-center">
                {{ $t('reset-password.title') }}
            </div>
        </div>
        <q-card-section>
            <p class="text-body1 text-grey-8 q-pb-lg text-center">
                {{ $t('reset-password.text', [email]) }}
            </p>
            <q-banner
                v-if="errorMessage"
                dark
                rounded
                class="bg-negative q-mb-lg"
            >
                <template #avatar
                    ><q-icon name="mdi-alert-circle-outline"
                /></template>
                <div>{{ errorMessage.message }}</div>
            </q-banner>
            <q-banner
                v-if="showStatusMessage"
                dark
                rounded
                class="bg-secondary q-mb-lg"
            >
                <template #avatar
                    ><q-icon name="mdi-mailbox-up-outline"
                /></template>
                <div>{{ statusMessage }}</div>
            </q-banner>
            <q-form class="q-gutter-md" autofocus @submit="reset">
                <PasswordWithToggleInput
                    v-model="password"
                    outlined
                    :label="$t('common.your-password')"
                    :rules="passwordRules"
                    data-cy="password"
                    @focus="errorMessage = null"
                />
                <PasswordWithToggleInput
                    v-model="password_confirmation"
                    outlined
                    :label="$t('common.confirm-password')"
                    :rules="passwordConfirmationRules"
                    data-cy="password"
                    @focus="errorMessage = null"
                />
                <div class="flex justify-end">
                    <q-btn
                        :label="$t('common.reset')"
                        type="submit"
                        color="primary"
                        data-cy="login"
                        :loading="loading"
                    />
                </div>
            </q-form>
        </q-card-section>
    </q-card>
    <q-card v-else flat>
        <div class="flex justify-around q-mb-none">
            <q-icon name="mdi-lock-reset" color="primary" size="4rem" />
        </div>
        <div class="text-primary">
            <div class="text-h5 text-center">
                {{ $t('reset-password.invalid-request-title') }}
            </div>
        </div>
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';
import { locale, SanctumResetPasswordRequest, useSanctum } from '@/api/sanctum';
import PasswordWithToggleInput from '@/components/PasswordWithToggleInput.vue';

const sanctum = useSanctum();
const router = useRouter();
const { t } = useI18n();
const localeStore = useLocaleStore();

//get email and token from query string
const email = ref((router.currentRoute.value.query?.email as string) || '');
const token = ref((router.currentRoute.value.query?.token as string) || '');
const canReset = computed(() => {
    return email.value !== '' && token.value !== '';
});

// UI related data
const loading = ref(false);
const errorMessage: Ref<ErrorResponse | null> = ref(null);
const statusMessage = ref('');
const password = ref('');
const password_confirmation = ref('');

async function reset() {
    loading.value = true;

    const data: SanctumResetPasswordRequest = {
        email: email.value,
        token: token.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        locale: localeStore.locale as locale,
    };

    await sanctum
        .resetPassword(data)
        .then((resp) => {
            console.log(resp);
            statusMessage.value = resp.data.status;
            setTimeout(() => {
                router.push({ name: 'login', query: { email: email.value } });
            }, 5000);
        })
        .catch((err) => {
            errorMessage.value = extractErrorMessages(err);
        });
    loading.value = false;
}

// verified email section
const showStatusMessage = computed(() => {
    return statusMessage.value !== '';
});

// rules
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
