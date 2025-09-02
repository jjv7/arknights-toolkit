<template>
    <v-container class="mx-auto">
        <h1 class="text-h2 font-weight-bold text-center mb-4">About Us</h1>
        <v-divider class="mb-8" />
        <v-row align="center" justify="center" class="mx-auto">
            <v-col cols="12" md="6" lg="8" class="pr-8">
                <AboutText />
            </v-col>
            <v-col cols="12" md="6" lg="4">
                <AmiyaSelector />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import AmiyaSelector from '../components/about/AmiyaSelector.vue';
import AboutText from '../components/about/AboutText.vue';

export default {
    name: 'About',
    components: {
        AboutText,
        AmiyaSelector
    }
}
</script>