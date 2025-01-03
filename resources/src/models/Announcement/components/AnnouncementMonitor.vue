<script setup lang="ts">
import { useQuasar } from 'quasar'
import { type AnnouncementResource, type AnnouncementResourceList, AnnouncementService } from '../Announcement'

const localeStore = useLocaleStore()
const { t } = useI18n()

const muted = useLocalStorage<number[]>('mutedAnnouncementId', [])
const announcements = ref<AnnouncementResourceList | null>(null)
const { resume, pause } = useTimeoutPoll(updateStatus, 600000) // 10 minute in ms

onMounted(async () => {
  resume()
})

async function updateStatus() {
  try {
    announcements.value = await AnnouncementService.list()

    if (!announcements.value) {
      return
    }

    for (const announcement of announcements.value.data) {
      if (muted.value.includes(announcement.data.id)) {
        continue
      }
      notify(announcement)
    }
  }
  catch (err) {
    console.error(err)
    pause()
  }
}

const $q = useQuasar()

function notify(announcement: AnnouncementResource) {
  const htmlMessage = `
        <div class="text-body1">${announcement.data[`title_${localeStore.locale}`]}</div>
        <div class="text-caption">${
          useLocaleDate(announcement.data.start_at).value
        }</div>
        <div class="text-body2">${announcement.data[`text_${localeStore.locale}`]}</div>`

  $q.notify({
    multiLine: true,
    html: true,
    icon: 'mdi-progress-wrench',
    iconSize: '4rem',
    message: htmlMessage,
    color: 'secondary',
    position: 'bottom-right',
    timeout: 0,
    actions: [
      {
        label: t('common.dismiss'),
        color: 'white',
        handler() {
          muted.value.push(announcement.data.id)
        },
      },
    ],
  })
}

function unmutedAnnouncement() {
  muted.value = []
  updateStatus()
}
</script>

<template>
  <q-btn
    flat
    round
    dense
    size="sm"
    :color="announcements?.data.length ? 'secondary' : 'grey-7'"
    :disable="!announcements"
    :icon="announcements?.data.length ? 'mdi-bell-badge' : 'mdi-bell-outline'"
    class="q-mb-xs"
    @click="unmutedAnnouncement"
  >
    <q-tooltip anchor="top left" self="bottom right">
      <span v-if="!announcements">{{ $t('common.no_announcements') }}</span>
      <span v-else>{{ $t('common.announcements', { count: announcements.data.length }) }}</span>
    </q-tooltip>
  </q-btn>
</template>

<style scoped></style>
