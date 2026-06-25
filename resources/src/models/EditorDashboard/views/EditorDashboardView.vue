<script setup lang="ts">
import type {
  EditorDashboardCounts,
  EditorQueuePublication,
} from '../EditorDashboard'
import ContentCard from '@/components/ContentCard.vue'
import CopyToClipboardButton from '@/components/CopyToClipboardButton.vue'
import MetricCard from '@/components/MetricCard.vue'
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue'
import PaginationDiv from '@/components/PaginationDiv.vue'
import { useLocaleTimeAgo } from '@/composables/useLocaleTimeAgo'
import { EditorDashboardService } from '../EditorDashboard'

const { t } = useI18n()
const localeStore = useLocaleStore()

const counts = ref<EditorDashboardCounts>()
const queue = ref<EditorQueuePublication[]>([])
const meta = ref()
const currentPage = ref(1)
const loading = ref(false)

const metrics = computed(() => [
  {
    title: t('editor-dashboard.awaiting-single-window'),
    value: counts.value?.awaiting_single_window ?? 0,
    subtitle: t('editor-dashboard.awaiting-single-window-subtitle'),
  },
  {
    title: t('editor-dashboard.in-single-window'),
    value: counts.value?.in_single_window ?? 0,
    subtitle: t('editor-dashboard.in-single-window-subtitle'),
  },
  {
    title: t('editor-dashboard.in-planning-binder'),
    value: counts.value?.in_planning_binder ?? 0,
    subtitle: t('editor-dashboard.in-planning-binder-subtitle'),
  },
])

function regionName(pub: EditorQueuePublication): string {
  const region = pub.region?.data
  if (!region) {
    return ''
  }
  return localeStore.locale === 'fr' ? region.name_fr : region.name_en
}

function ageInDays(acceptedOn: string | null): number | null {
  if (!acceptedOn) {
    return null
  }
  const accepted = new Date(acceptedOn).getTime()
  if (Number.isNaN(accepted)) {
    return null
  }
  const diff = Date.now() - accepted
  return Math.max(0, Math.floor(diff / (1000 * 60 * 60 * 24)))
}

async function getDashboard() {
  if (loading.value) {
    return
  }
  loading.value = true
  try {
    const response = await EditorDashboardService.get(currentPage.value)
    counts.value = response.counts
    queue.value = response.data.map(row => row.data)
    meta.value = response.meta ?? null
  }
  finally {
    loading.value = false
  }
}

watch(currentPage, () => {
  getDashboard()
})

onMounted(() => {
  getDashboard()
})
</script>

<template>
  <div class="q-pa-md">
    <div class="row">
      <div
        v-for="metric in metrics"
        :key="metric.title"
        class="col-12 col-sm-4 q-pa-sm"
      >
        <MetricCard
          :title="metric.title"
          :value="metric.value"
          :subtitle="metric.subtitle"
          animate
        />
      </div>
    </div>

    <div class="q-pa-sm">
      <ContentCard>
        <template #title>
          <q-icon name="mdi-tray-full" color="primary" size="sm" left />
          <span>{{ $t('editor-dashboard.due-queue') }}</span>
        </template>
        <template #subtitle>
          {{ $t('editor-dashboard.due-queue-subtitle') }}
        </template>

        <template v-if="queue.length === 0 && !loading">
          <NoResultFoundDiv />
        </template>

        <q-markup-table v-if="queue.length > 0" flat>
          <thead>
            <tr>
              <th class="text-left">
                {{ $t('common.title') }}
              </th>
              <th class="text-left">
                {{ $t('common.contact') }}
              </th>
              <th class="text-left">
                {{ $t('editor-dashboard.accepted-on') }}
              </th>
              <th class="text-left">
                {{ $t('editor-dashboard.age') }}
              </th>
              <th class="text-center">
                {{ $t('editor-dashboard.planning-binder') }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="pub in queue"
              :key="pub.id"
              class="cursor-pointer"
              @click="$router.push(`/publication/${pub.id}`)"
            >
              <td class="text-left">
                <div class="queue-title" :title="pub.title">
                  {{ pub.title }}
                </div>
                <div
                  v-if="pub.catalogue_number"
                  class="text-caption text-grey-7 q-mt-xs"
                  :title="pub.catalogue_number"
                >
                  {{ $t('common.catalogue-number') }}: {{ pub.catalogue_number }}
                </div>
              </td>
              <td class="text-left">
                <div class="row items-center no-wrap q-gutter-xs">
                  <span class="queue-contact-name" :title="pub.contact_name ?? '-'">
                    {{ pub.contact_name ?? '-' }}
                  </span>
                  <template v-if="pub.contact_email">
                    <a
                      :href="`mailto:${pub.contact_email}`"
                      class="text-primary"
                      @click.stop
                    >({{ pub.contact_email }})</a>
                    <CopyToClipboardButton
                      size="xs"
                      :text="pub.contact_email"
                      class="q-pa-none"
                      @click.stop
                    />
                  </template>
                </div>
                <div class="text-caption text-grey-7 q-mt-xs">
                  {{ regionName(pub) }}
                </div>
              </td>
              <td class="text-left">
                {{ useLocaleDate(pub.accepted_on).value }}
              </td>
              <td class="text-left">
                <template v-if="pub.accepted_on">
                  {{ ageInDays(pub.accepted_on) }}
                  {{ $t('common.timeAgo.day', ageInDays(pub.accepted_on) ?? 0) }}
                  <span class="text-grey-7">
                    ({{ useLocaleTimeAgo(new Date(pub.accepted_on)).value }})
                  </span>
                </template>
                <span v-else class="text-grey-8">-</span>
              </td>
              <td class="text-center">
                <q-icon
                  v-if="pub.in_planning_binder"
                  name="mdi-flag-outline"
                  color="red"
                  size="sm"
                >
                  <q-tooltip>
                    {{ $t('editor-dashboard.flagged-tooltip') }}
                  </q-tooltip>
                </q-icon>
              </td>
            </tr>
          </tbody>
        </q-markup-table>

        <q-card-section>
          <q-separator class="q-mb-md" />
          <PaginationDiv
            v-model="currentPage"
            :meta="meta ?? null"
            :disable="loading"
          />
        </q-card-section>
      </ContentCard>
    </div>
  </div>
</template>

<style scoped>
.queue-title {
  max-width: 32ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.queue-contact-name {
  max-width: 18ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
