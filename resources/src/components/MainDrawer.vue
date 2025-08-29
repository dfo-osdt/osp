<script setup lang="ts">
import type { MenuItem } from './DrawerMenuItem.vue'
import { QDrawer, QItemLabel, QList, QSeparator } from 'quasar'
import DrawerMenuItem from './DrawerMenuItem.vue'

const authStore = useAuthStore()
const { t } = useI18n()

const { height } = useWindowSize()
const displayBottomMenu = computed(() => {
  return height.value >= 600
})

// Permission check for regional manuscripts is now in AuthStore

const userMenuItems = computed<MenuItem[]>(() => {
  return [
    {
      icon: 'mdi-view-dashboard',
      label: t('common.dashboard'),
      to: '/dashboard',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-file-document-multiple',
      label: t('common.my-manuscripts'),
      to: '/my-manuscripts',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-newspaper-variant-multiple-outline',
      label: t('common.my-publications'),
      to: '/my-publications',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-file-sign',
      label: t('common.my-reviews'),
      to: '/my-reviews',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
  ]
})

const exploreMenuItems = computed<MenuItem[]>(() => {
  return [
    {
      icon: 'mdi-newspaper-variant-multiple-outline',
      label: t('common.publications'),
      to: '/publications',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-file-document-edit-outline',
      label: t('common.regional-manuscripts'),
      to: '/regional-manuscripts',
      visible: authStore.canViewRegionalManuscripts,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-cloud-print-outline',
      label: t('common.preprints'),
      to: '/preprints',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-account-group-outline',
      label: t('common.author', 2),
      to: '/authors',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
  ]
})

const bottomMenuItems = computed<MenuItem[]>(() => {
  return [
    {
      icon: 'mdi-badge-account-outline',
      label: t('SettingsPage.author-profile'),
      to: '/settings/author',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
    {
      icon: 'mdi-logout',
      label: t('common.logout'),
      to: '/auth/logout',
      visible: true,
      tooltipVisible: authStore.isDrawerMini,
    },
  ]
})
</script>

<template>
  <QDrawer
    :width="300"
    :mini="authStore.isDrawerMini"
    class="column justify-between"
    mini-to-overlay
    @mouseover="authStore.isDrawerMini = false"
    @mouseout="authStore.isDrawerMini = true"
  >
    <QList padding class="menu-list" role="menubar">
      <DrawerMenuItem
        v-for="item in userMenuItems.filter(item => item.visible)"
        :key="item.label"
        :item="item"
        role="menuitem"
      />

      <QSeparator />
      <QItemLabel header class="q-mt-md">
        {{ $t('common.explore') }}
      </QItemLabel>

      <DrawerMenuItem
        v-for="item in exploreMenuItems.filter(item => item.visible)"
        :key="item.label"
        :item="item"
        role="menuitem"
      />
    </QList>
    <QList v-if="displayBottomMenu" class="q-mb-sm" role="menubar">
      <QSeparator />
      <DrawerMenuItem
        v-for="item in bottomMenuItems.filter(item => item.visible)"
        :key="item.label"
        :item="item"
        role="menuitem"
      />
    </QList>
  </QDrawer>
</template>
