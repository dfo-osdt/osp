<template>
    <div class="flex row justify-center">
        <div class="col-12 col-lg-10 flex column text-center">
            <div>
                <q-img
                    src="/assets/splash_images/undraw_road_sign_re_3kc3.svg"
                    width="250px"
                />
            </div>
            <div class="text-h5 text-weight-light text-accent q-mb-sm">
                {{ title }}
            </div>
            <div class="text-body1 text-grey-8 text-left">
                <p>
                    Need to publish a primary or secondary publication? You've
                    come to the right place. Create a manuscript record and
                    start the process of publishing your manuscript following
                    the national policy for science publications.
                </p>

                <p>
                    The national policy ensures a consistent treatment of
                    scientific publications across DFO regions, recognition of
                    the scientific impact of the Department through the use of a
                    standard affiliation, and effective tracking of our
                    publications to better integrate science into departmental
                    processes.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<style scoped></style>
