<template>
    <ContentCard>
        <template #title>{{ $t('SettingsPage.author-profile') }}</template>
        <template v-if="author">
            <q-form ref="form" @submit="save">
                <div class="row q-col-gutter-md">
                    <q-input
                        v-model="author.data.first_name"
                        class="col-12 col-md-6"
                        :label="$t('common.first-name')"
                        outlined
                        :disable="hasOwner"
                        :rules="[
                            (val) => val.length > 0 || t('common.required'),
                        ]"
                        :show-hint="hasOwner"
                        :hint="
                            $t(
                                'manage-author-profile-card.synced-to-your-user-profile'
                            )
                        "
                    />
                    <q-input
                        v-model="author.data.last_name"
                        class="col-12 col-md-6"
                        :label="$t('common.last-name')"
                        outlined
                        :disable="hasOwner"
                        :rules="[
                            (val) => val.length > 0 || t('common.required'),
                        ]"
                        :show-hint="hasOwner"
                        :hint="
                            $t(
                                'manage-author-profile-card.synced-to-your-user-profile'
                            )
                        "
                    />
                    <q-input
                        v-model="author.data.email"
                        class="col-12 col-md-6"
                        :label="$t('common.email')"
                        outlined
                        :disable="true"
                        :show-hint="hasOwner"
                        :hint="
                            $t(
                                'manage-author-profile-card.synced-to-your-user-profile'
                            )
                        "
                    />
                    <OrganizationSelect
                        v-model="author.data.organization_id"
                        class="col-12 col-md-6"
                        :label="$t('common.current-affiliation')"
                        outlined
                        :hint="
                            $t(
                                'manage-author-profile-card.current-affiliation-hint'
                            )
                        "
                        clearable
                        :rules="[(val) => val !== null || t('common.required')]"
                    />
                    <OrcidInput
                        v-model="author.data.orcid"
                        class="col-12 col-md-6"
                    />
                </div>
                <q-card-actions align="right">
                    <q-btn
                        color="primary"
                        :label="$t('common.save')"
                        :loading="loading"
                        type="submit"
                    />
                </q-card-actions>
            </q-form>
        </template>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import { AuthorResource, AuthorService } from '../Author';
import { QForm, QInput, useQuasar } from 'quasar';
import OrganizationSelect from '@/models/Organization/components/OrganizationSelect.vue';
import OrcidInput from '@/components/OrcidInput.vue';

const $q = useQuasar();
const { t } = useI18n();
const form = ref<QForm | null>(null);
const props = defineProps<{
    authorId: number;
}>();

const loading = ref(true);
const author: Ref<AuthorResource | null> = ref(null);

const hasOwner = computed(() => {
    return author.value?.data.user_id !== null;
});

async function getAuthor() {
    loading.value = true;
    author.value = await AuthorService.find(props.authorId);
    loading.value = false;
}

onMounted(async () => {
    getAuthor();
});

async function save() {
    if (!author.value) return;
    loading.value = true;
    await AuthorService.update(author.value.data)
        .then((resp) => {
            $q.notify({
                type: 'positive',
                message: t('common.saved'),
            });
            author.value = resp;
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {
            loading.value = false;
        });
}
</script>

<style scoped></style>
