<script setup lang="ts">
import { useQuasar } from 'quasar'

const $q = useQuasar()
const authStore = useAuthStore()
</script>

<template>
  <q-page>
    <section class="row q-py-xl justify-center">
      <div class="col-11 col-lg-10">
        <div class="row">
          <div class="col-4 gt-sm">
            <q-img
              src="/assets/ideas_grow.png"
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
    <section>
      <div />
    </section>
  </q-page>
</template>

<style></style>
