<template>
    <q-footer bordered class="bg-white text-grey-7 q-py-sm">
        <div class="row justify-between q-mx-lg">
            <div>&nbsp;</div>
            <div>
                <a
                    class="text-caption text-primary contact-link"
                    href="mailto:DFO.OpenScience-ScienceOuverte.MPO@dfo-mpo.gc.ca"
                    >{{ $t('common.contact-us') }}</a
                >
            </div>
            <div class="text-caption">&copy; {{ year }} OSP-PSO</div>
        </div>
    </q-footer>
</template>

<script setup lang="ts">
const year = new Date().getFullYear();
</script>

<style scoped>
.contact-link {
    text-decoration: none;
}
.contact-link:hover {
    text-decoration: underline;
}
</style>
