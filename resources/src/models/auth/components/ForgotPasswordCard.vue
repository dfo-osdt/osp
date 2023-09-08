<template>
    <q-card flat>
        <div class="flex justify-around q-mb-none">
            <q-icon name="mdi-lock-reset" color="primary" size="4rem" />
        </div>
        <div class="text-primary">
            <div class="text-h5 text-center">
                {{ $t('forgot-password-card.title') }}
            </div>
        </div>
        <q-card-section>
            <p class="text-body1 text-grey-8 q-pb-lg">
                {{ $t('forgot-password-card.text') }}
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
            <q-form
                v-if="!hideForm"
                class="q-gutter-md"
                autofocus
                @submit="reset"
            >
                <q-input
                    v-model="email"
                    type="email"
                    outlined
                    :label="$t('common.your-email')"
                    lazy-rules
                    :rules="emailRules"
                    data-cy="email"
                    @focus="errorMessage = null"
                />
                <div class="flex justify-end">
                    <q-btn
                        :label="$t('common.reset')"
                        type="submit"
                        color="primary"
                        data-cy="reset-password"
                        :loading="loading"
                    />
                    <q-btn
                        :label="$t('forgot-password-card.cancel')"
                        color="primary"
                        flat
                        data-cy="cancel"
                        @click="router.push({ name: 'login' })"
                    />
                </div>
            </q-form>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';
import { useSanctum } from '@/api/sanctum';

const sanctum = useSanctum();
const router = useRouter();
const { t } = useI18n();
const localeStore = useLocaleStore();

//user related data
const email = ref((router.currentRoute.value.query?.email as string) || '');

// UI related data
const loading = ref(false);
const hideForm = ref(false);
const errorMessage: Ref<ErrorResponse | null> = ref(null);
const statusMessage = ref('');

async function reset() {
    loading.value = true;
    await sanctum
        .forgotPassword(email.value, localeStore.locale)
        .then((resp) => {
            console.log(resp);
            statusMessage.value = resp.data.status;
            hideForm.value = true;
            setTimeout(() => {
                statusMessage.value = '';
                email.value = '';
                hideForm.value = false;
            }, 30000);
        })
        .catch((err) => {
            errorMessage.value = extractErrorMessages(err);
            setTimeout(() => {
                errorMessage.value = null;
            }, 5000);
        });
    loading.value = false;
}

// verified email section
const showStatusMessage = computed(() => {
    return statusMessage.value !== '';
});

// rules
const emailRules = computed(() => [
    (val: string) => !!val || t('common.validation.email-required'),
    // must be valid email
    (val: string) =>
        /^\S+@\S+\.\S+$/.test(val) || t('common.validation.email-invalid'),
]);
</script>

<style scoped></style>
