<script setup lang="ts">
import { useQuasar } from 'quasar'
import { computed, onMounted, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { http } from '@/api/http'
import MetricCard from '@/components/MetricCard.vue'
import OrcidIcon from '@/components/OrcidIcon.vue'
import RorIcon from '@/components/RorIcon.vue'

const $q = useQuasar()
const { t } = useI18n()
const authStore = useAuthStore()
const localeStore = useLocaleStore()

interface PublicStats {
  publications_count: number
  manuscripts_reviewed_count: number
  authors_count: number
  external_authors_count: number
  orcid_connected_count: number
  external_organizations: Array<{
    id: number
    name_en: string
    name_fr: string
    ror_identifier: string
    authors_count: number
  }>
  recent_publications: Array<{
    id: number
    title: string
    doi: string
    published_on: string
    journal: { id: number, title: string } | null
    authors: Array<{
      first_name: string
      last_name: string
      orcid: string
      orcid_verified: boolean
    }>
  }>
}

const stats = ref<PublicStats | null>(null)

type MetricKey = 'authors_count' | 'external_authors_count' | 'orcid_connected_count' | 'manuscripts_reviewed_count' | 'publications_count'

const metrics = computed(() => [
  { key: 'authors_count' as MetricKey, title: t('osp.stats.authors'), subtitle: t('osp.stats.contributing') },
  { key: 'external_authors_count' as MetricKey, title: t('osp.stats.external-authors'), subtitle: t('osp.stats.external-contributing') },
  { key: 'orcid_connected_count' as MetricKey, title: t('osp.stats.orcid-connected'), subtitle: t('osp.stats.linked-profiles') },
  { key: 'manuscripts_reviewed_count' as MetricKey, title: t('osp.stats.manuscripts-reviewed'), subtitle: t('osp.stats.completed-review') },
  { key: 'publications_count' as MetricKey, title: t('osp.stats.publications'), subtitle: t('osp.stats.published') },
])

onMounted(async () => {
  try {
    const response = await http.get<PublicStats>('/api/stats')
    stats.value = response.data
  }
  catch {
    // stats are non-critical — fail silently
  }
})

function journalTitle(journal: PublicStats['recent_publications'][number]['journal']): string {
  return journal?.title ?? ''
}

function orgName(org: PublicStats['external_organizations'][number]): string {
  return localeStore.locale === 'fr' ? org.name_fr : org.name_en
}

function authorFullName(author: PublicStats['recent_publications'][number]['authors'][number]): string {
  return `${author.last_name}, ${author.first_name}`
}
</script>

<template>
  <q-page>
    <section class="row q-py-xl justify-center" aria-labelledby="hero-heading">
      <div class="col-11 col-lg-10">
        <div class="row">
          <div class="col-4 gt-sm">
            <q-img
              src="/assets/ideas_grow.png"
              fetch-priority="high"
              loading-show-delay="100"
              width="100%"
              :alt="$t('osp.alt.main-image')"
            />
          </div>
          <div
            class="col-12 col-md-7 q-mt-xl"
            :class="$q.screen.lt.md ? 'text-center' : ''"
          >
            <h1 id="hero-heading" class="text-weight-medium q-mt-none q-mb-none">
              <span class="text-h2">{{ $t('osp.slogan.p1') }},</span>
              <br>
              <span class="text-h1 text-weight-medium">
                {{ $t('osp.slogan.p2')
                }}<span class="text-primary">&nbsp;{{ $t('osp.slogan.p3') }}</span>.
              </span>
            </h1>
            <p class="q-my-lg text-h5 text-grey-8">
              {{ $t('osp.description') }}
            </p>
            <div
              v-if="!authStore.isAuthenticated"
              class="row q-mt-xl"
              :class="$q.screen.lt.md ? 'justify-center' : ''"
            >
              <q-btn
                size="lg"
                color="primary"
                :label="$t('common.login')"
                :to="{ name: 'login' }"
              />
              <q-btn
                v-if="!authStore.openAuthOnly"
                size="lg"
                class="q-ml-md"
                outline
                :label="$t('common.register')"
                :to="{ name: 'register' }"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
    <section v-if="stats" class="row q-py-xl justify-center bg-grey-2" aria-labelledby="stats-heading">
      <div class="col-11 col-lg-10">
        <h2 id="stats-heading" class="text-h4 text-weight-medium q-mb-lg text-center q-mt-none">
          {{ $t('osp.stats.by-the-numbers') }}
        </h2>
        <div class="row q-col-gutter-md justify-center">
          <div
            v-for="metric in metrics"
            :key="metric.key"
            class="col-12 col-sm-4"
          >
            <MetricCard
              :title="metric.title"
              :value="stats[metric.key]"
              :subtitle="metric.subtitle"
              suffix="+"
              animate
            />
          </div>
        </div>
        <div
          v-if="stats.external_organizations.length"
          class="q-mt-lg text-center"
        >
          <h3 class="text-subtitle1 text-weight-medium q-mb-sm q-mt-none">
            {{ $t('osp.stats.external-organizations') }}
          </h3>
          <div class="q-gutter-sm">
            <a
              v-for="org in stats.external_organizations"
              :key="org.id"
              :href="org.ror_identifier"
              target="_blank"
              rel="noopener noreferrer"
              :aria-label="$t('osp.a11y.org-external-link', { name: orgName(org) })"
              class="text-decoration-none"
            >
              <q-chip
                clickable
                outline
                color="primary"
              >
                <RorIcon size="18px" class="q-mr-xs" />
                {{ orgName(org) }}
              </q-chip>
            </a>
          </div>
        </div>
      </div>
    </section>
    <section
      v-if="stats && stats.recent_publications.length"
      class="row q-py-xl justify-center bg-grey-1"
      aria-labelledby="publications-heading"
    >
      <div class="col-11 col-lg-10">
        <h2 id="publications-heading" class="text-h4 text-weight-medium q-mb-lg text-center q-mt-none">
          {{ $t('osp.stats.recent-publications') }}
        </h2>
        <q-card flat bordered>
          <q-list separator>
            <q-item
              v-for="pub in stats.recent_publications"
              :key="pub.id"
            >
              <q-item-section>
                <q-item-label class="text-body1 text-weight-medium text-primary">
                  {{ pub.title }}
                </q-item-label>
                <q-item-label v-if="pub.authors?.length" caption lines="2">
                  <span
                    v-for="(author, index) in pub.authors"
                    :key="index"
                  >
                    <a
                      v-if="author.orcid"
                      :href="author.orcid"
                      target="_blank"
                      rel="noopener noreferrer"
                      :aria-label="$t('osp.a11y.orcid-external-link', { name: authorFullName(author) })"
                      class="q-ml-xs"
                    ><OrcidIcon :unauthenticated="!author.orcid_verified" /></a>
                    {{ author.last_name }}, {{ author.first_name
                    }}<span v-if="index < pub.authors.length - 1">;&#32;</span>
                  </span>
                </q-item-label>
                <q-item-label caption>
                  <div class="q-pt-sm flex flex-wrap">
                    <div>
                      {{ new Date(pub.published_on).getFullYear() }}
                    </div>
                    <div class="q-mx-sm">
                      |
                    </div>
                    <div>
                      {{ journalTitle(pub.journal) || '-' }}
                    </div>
                    <div class="q-mx-sm">
                      |
                    </div>
                    <div>
                      {{ $t('common.doi') }}:
                      <a
                        :href="`https://doi.org/${pub.doi}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        :aria-label="$t('osp.a11y.doi-external-link', { doi: pub.doi, title: pub.title })"
                      >{{ pub.doi }}</a>
                    </div>
                  </div>
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card>
      </div>
    </section>
  </q-page>
</template>

