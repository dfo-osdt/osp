<template>
    <q-page>
        <section class="row q-py-xl justify-center bg-grey-12">
            <div class="col-12 col-lg-10">
                <div class="row">
                    <div class="col-5 gt-sm">
                        <q-img src="/assets/ideas_grow.png" width="30rem" />
                    </div>
                    <div
                        class="col-12 col-md-6 q-mt-xl"
                        :class="$q.screen.lt.lg ? 'text-center' : ''"
                    >
                        <div class="text-h2">Science is amazing,</div>
                        <div class="text-h1 text-weight-medium align-end">
                            Help it <span class="text-teal-5">grow</span>.
                        </div>
                        <div
                            class="row q-mt-xl"
                            :class="$q.screen.lt.lg ? 'justify-center' : ''"
                        >
                            <q-btn
                                size="lg"
                                color="teal-5"
                                label="Get Started"
                                to="/register"
                            />
                            <q-btn
                                size="lg"
                                class="q-ml-lg"
                                outline
                                label="Login"
                                to="/login"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </q-page>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
const $q = useQuasar();
</script>
