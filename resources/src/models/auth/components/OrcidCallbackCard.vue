<template>
    <q-card>
        <q-card-section class="flex flex-center column">
            <template v-if="loading">
                <h5>{{ $t('orcid.verifying') }}</h5>
                <q-spinner-dots size="50px" color="primary" />
            </template>
            <template v-else-if="valid">
                <q-icon
                    name="mdi-check-decagram-outline"
                    size="50px"
                    color="primary"
                    class="q-mb-md"
                />
                <h5 class="q-my-none text-center">
                    {{ $t('orcid.verified-with') }}
                </h5>

                <h6 class="q-mt-md">{{ author?.orcid }}</h6>
                <q-btn
                    label="Continue"
                    color="primary"
                    @click="
                        $router.push({
                            name: 'settings.author',
                        })
                    "
                />
            </template>
            <template v-else>
                <q-icon name="error" size="50px" color="red" />
                <h5>{{ $t('orcid.error-verifying') }}</h5>
            </template>
        </q-card-section>
    </q-card>
</template>

<script setup lang="ts">
import { Author, AuthorService } from '@/models/Author/Author';

const router = useRouter();
const authStore = useAuthStore();

const accessToken = ref(
    (router.currentRoute.value.query?.access_token as string) || ''
);

const loading = ref(true);
const valid = ref(false);
const author = ref<Author | null>(null);

async function verifyWithBackend() {
    AuthorService.verifyOrcid(accessToken.value)
        .then((response) => {
            console.log(response);
            valid.value = true;
            author.value = response.data;
            authStore.refreshUser(true);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
        });
}

onMounted(() => {
    console.log('mounted');
    verifyWithBackend();
});
</script>

<style scoped></style>
