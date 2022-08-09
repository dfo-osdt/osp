<template>
    <q-page class="flex flex-center">
        <div class="row fit justify-center">
            <div class="col-10 col-sm-6 col-md-5 col-lg-4">
                <LoginCard />
            </div>
        </div>
    </q-page>
</template>

<script setup lang="ts">
import LoginCard from '../../models/auth/components/LoginCard.vue';
</script>

<style scoped></style>
