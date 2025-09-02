<template>
    <v-container class="mx-auto mb-4">
        <v-card class="pa-8">
            <v-row justify="center" align="center">
                <v-col cols="12" md="6" lg="4">
                    <v-img
                        :src="getImageUrl('arknights-toolkit-icon.png')"
                        max-width="450px"
                        align="center"
                        class="mx-auto"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="8" class="d-flex align-center">
                    <h3 class="font-weight-medium text-center text-h6 text-lg-h5">
                        Welcome to AK Toolkit — your all-in-one website for everything Arknights.
                        Whether you're planning your sanity usage, searching for the most efficient
                        farming stages, or keeping up with the latest banners and limited-time events,
                        we've got the tools to help you stay ahead. With live drop-rate data powered by
                        Penguin Statistics and a hub for community-shared strategies and walkthroughs,
                        AK Toolkit empowers Doctors to make informed decisions, optimise their resources,
                        and get the most out of every operation.
                    </h3>
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
export default {
    name: 'WelcomeSection',
    methods: {
        getImageUrl(name) {
            return new URL(`/src/assets/${name}`, import.meta.url).href
        }
    }
}
</script>