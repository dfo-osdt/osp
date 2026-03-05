<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import { http } from '@/api/http'
import { useCountUp } from '@/composables/useCountUp'
import OrcidIcon from '@/components/OrcidIcon.vue'

const $q = useQuasar()
const authStore = useAuthStore()
const localeStore = useLocaleStore()

interface PublicStats {
  publications_count: number
  manuscripts_reviewed_count: number
  authors_count: number
  top_organizations: Array<{
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
    journal: { id: number; title: string } | null
    authors: Array<{
      first_name: string
      last_name: string
      orcid: string
      orcid_verified: boolean
    }>
  }>
}

const stats = ref<PublicStats | null>(null)

const publicationsTarget = computed(() => stats.value?.publications_count ?? 0)
const manuscriptsTarget = computed(() => stats.value?.manuscripts_reviewed_count ?? 0)
const authorsTarget = computed(() => stats.value?.authors_count ?? 0)

const publicationsCount = useCountUp(publicationsTarget)
const manuscriptsCount = useCountUp(manuscriptsTarget)
const authorsCount = useCountUp(authorsTarget)

onMounted(async () => {
  try {
    const response = await http.get<PublicStats>('/api/stats')
    stats.value = response.data
  } catch {
    // stats are non-critical — fail silently
  }
})

function journalTitle(journal: PublicStats['recent_publications'][number]['journal']): string {
  return journal?.title ?? ''
}

function orgName(org: PublicStats['top_organizations'][number]): string {
  return localeStore.locale === 'fr' ? org.name_fr : org.name_en
}
</script>

<template>
  <q-page>
    <section class="row q-py-xl justify-center">
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
            <div class="text-h2">
              {{ $t('osp.slogan.p1') }},
            </div>
            <div class="text-h1 text-weight-medium align-end">
              {{ $t('osp.slogan.p2')
              }}<span class="text-primary">&nbsp;{{ $t('osp.slogan.p3') }}</span>.
            </div>
            <div class="q-my-lg text-h5 text-grey-8">
              {{ $t('osp.description') }}
            </div>
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
    <section v-if="stats" class="row q-py-xl justify-center bg-grey-2">
      <div class="col-11 col-lg-10">
        <div class="text-h4 text-weight-medium q-mb-lg text-center">
          {{ $t('osp.stats.by-the-numbers') }}
        </div>
        <div class="row q-col-gutter-md justify-center">
          <div class="col-12 col-sm-4">
            <q-card flat bordered>
              <q-card-section class="q-pb-sm">
                <div class="text-body1 text-weight-medium text-primary">
                  {{ $t('osp.stats.publications') }}
                </div>
              </q-card-section>
              <q-card-section class="q-py-none">
                <div class="text-h3 text-weight-medium">
                  {{ publicationsCount }}+
                </div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <div class="text-body1 text-grey-8">
                  {{ $t('osp.stats.published') }}
                </div>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-12 col-sm-4">
            <q-card flat bordered>
              <q-card-section class="q-pb-sm">
                <div class="text-body1 text-weight-medium text-primary">
                  {{ $t('osp.stats.manuscripts-reviewed') }}
                </div>
              </q-card-section>
              <q-card-section class="q-py-none">
                <div class="text-h3 text-weight-medium">
                  {{ manuscriptsCount }}+
                </div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <div class="text-body1 text-grey-8">
                  {{ $t('osp.stats.completed-review') }}
                </div>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-12 col-sm-4">
            <q-card flat bordered>
              <q-card-section class="q-pb-sm">
                <div class="text-body1 text-weight-medium text-primary">
                  {{ $t('osp.stats.authors') }}
                </div>
              </q-card-section>
              <q-card-section class="q-py-none">
                <div class="text-h3 text-weight-medium">
                  {{ authorsCount }}+
                </div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <div class="text-body1 text-grey-8">
                  {{ $t('osp.stats.contributing') }}
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
        <div
          v-if="stats.top_organizations.length"
          class="q-mt-lg text-center"
        >
          <div class="text-subtitle1 text-weight-medium q-mb-sm">
            {{ $t('osp.stats.top-organizations') }}
          </div>
          <div class="q-gutter-sm">
            <a
              v-for="org in stats.top_organizations"
              :key="org.id"
              :href="org.ror_identifier"
              target="_blank"
              rel="noopener"
              class="text-decoration-none"
            >
              <q-chip
                clickable
                outline
                color="primary"
              >
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
    >
      <div class="col-11 col-lg-10">
        <div class="text-h4 text-weight-medium q-mb-lg text-center">
          {{ $t('osp.stats.recent-publications') }}
        </div>
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
                      rel="noopener"
                      class="q-ml-xs"
                    ><OrcidIcon :unauthenticated="!author.orcid_verified" /></a>
                    {{ author.last_name }}, {{ author.first_name
                    }}<span v-if="index < pub.authors.length - 1">;&#32;</span>
                  </span>
                </q-item-label>
                <q-item-label caption>
                  <div class="q-pt-sm flex">
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
                      DOI:
                      <a
                        :href="`https://doi.org/${pub.doi}`"
                        target="_blank"
                        rel="noopener"
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

<style></style>
