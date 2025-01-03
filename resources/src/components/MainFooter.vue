<script setup lang="ts">
import AnnouncementMonitor from '@/models/Announcement/components/AnnouncementMonitor.vue'
</script>

<template>
  <q-footer bordered class="bg-white text-grey-7 q-py-sm">
    <div class="row justify-between q-mx-lg">
      <div>&nbsp;</div>
      <div>
        <a
          class="text-caption text-primary contact-link"
          href="mailto:DFO.OpenScience-ScienceOuverte.MPO@dfo-mpo.gc.ca"
        >{{ $t('common.contact-us') }}</a>
      </div>
      <div class="text-caption">
        <AnnouncementMonitor />
      </div>
    </div>
  </q-footer>
</template>

<style scoped>
.contact-link {
    text-decoration: none;
}
.contact-link:hover {
    text-decoration: underline;
}
</style>
