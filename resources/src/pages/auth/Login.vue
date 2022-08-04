<template>
    <q-page class="flex flex-center">
        <div class="row fit justify-center">
            <div class="col-4">
                <q-card>
                    <q-card-section class="bg-primary text-white">
                        <div class="text-h5">Login</div>
                    </q-card-section>
                    <q-card-section>
                        <q-form class="q-gutter-md" autofocus @submit="login">
                            <q-input
                                v-model="email"
                                type="email"
                                filled
                                label="Your email"
                            />
                            <q-input
                                v-model="password"
                                type="password"
                                filled
                                label="Your password"
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
            </div>
        </div>
    </q-page>
</template>

<script setup lang="ts">
import { extractErrorMessages } from '@/api/errors';
const { login: authLogin } = useAuthStore();
const router = useRouter();

const loading = ref(false);
const email = ref('');
const password = ref('');
const remember = ref(false);

async function login() {
    loading.value = true;
    await authLogin(email.value, password.value, remember.value)
        .then(() => {
            router.push('/');
            console.log('logged in');
        })
        .catch((err) => {
            console.log(extractErrorMessages(err));
        });
    loading.value = false;
}
</script>

<style scoped></style>
