<script setup lang="ts">
import type { ManuscriptRecordType } from '../ManuscriptRecord'

const value = defineModel<ManuscriptRecordType>({ required: true })
</script>

<template>
  <div>
    <div class="q-pa-md">
      <q-list>
        <q-item v-ripple tag="label">
          <q-item-section avatar>
            <q-radio v-model="value" val="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">
              {{ $t('common.primary-publication') }}
            </q-item-label>
            <q-item-label class="text-body2 text-grey-8">
              {{ $t('create-manuscript-record-dialog.primary-pub-desc') }}
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-item v-ripple tag="label">
          <q-item-section avatar top>
            <q-radio v-model="value" val="secondary" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">
              {{ $t('common.secondary-publication') }}
            </q-item-label>
            <q-item-label class="text-body2 text-grey-8">
              {{ $t('create-manuscript-record-dialog.secondary-pub-desc') }}
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-item v-ripple tag="label">
          <q-item-section avatar top>
            <q-radio v-model="value" val="preprint" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">
              {{ $t('common.preprint-publication') }}
            </q-item-label>
            <q-item-label class="text-body2 text-grey-8">
              {{ $t('create-manuscript-record-dialog.preprint-pub-desc') }}
            </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </div>
</template>

<style scoped></style>
