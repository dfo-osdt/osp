<template>
    <ContentCard secondary>
        <template #title>{{ $t('SettingsPage.user-roles') }}</template>
        <p>{{ $t('SettingsPage.user-roles-description') }}</p>
        <q-chip
            v-for="role in roles"
            :key="role"
            color="light-green-3"
            :label="role"
            class="q-mr-sm q-mb-sm"
        />
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { AuthenticatedUser } from '../AuthenticatedUser';

const localeStore = useLocaleStore();

const props = defineProps<{
    user: AuthenticatedUser;
}>();

const roles = computed(() => {
    const r: string[] = [];
    props.user.roles.forEach((role) => {
        r.push(props.user.getRoleNameForDisplay(role, localeStore.locale));
    });
    r.sort();
    return r;
});
</script>

<style scoped></style>
