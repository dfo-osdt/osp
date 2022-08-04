<template>
    <q-card>
        <q-card-section class="bg-primary text-white">
            <div class="text-h5">{{ t('common.login') }}</div>
        </q-card-section>
        <q-banner v-if="errorMessage" dark class="bg-negative q-mb-md">
            <template #avatar
                ><q-icon name="mdi-alert-circle-outline"
            /></template>
            <div>{{ errorMessage.message }}</div>
        </q-banner>
        <q-card-section>
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
                    @focus="errorMessage = null"
                />
                <q-input
                    v-model="password"
                    type="password"
                    filled
                    label="Your password"
                    :rules="[(val) => !!val || 'Password is required']"
                    @focus="errorMessage = null"
                />

                <q-toggle v-model="remember" label="Remember me" />

                <div>
                    <q-btn
                        label="Login"
                        type="submit"
                        color="primary"
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
    </q-card>
</template>

<script setup lang="ts">
import { ErrorResponse, extractErrorMessages } from '@/api/errors';
import { Ref } from 'vue';

const { login: authLogin } = useAuthStore();
const router = useRouter();
const { t } = useI18n();

//user related data
const email = ref('');
const password = ref('');
const remember = ref(false);

// UI related data
const loading = ref(false);
const errorMessage: Ref<ErrorResponse | null> = ref(null);

async function login() {
    loading.value = true;
    await authLogin(email.value, password.value, remember.value)
        .then(() => {
            router.push('/dashboard');
        })
        .catch((err) => {
            errorMessage.value = extractErrorMessages(err);
        });
    loading.value = false;
}
</script>

<style scoped></style>
