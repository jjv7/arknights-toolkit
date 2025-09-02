<template>
    <v-img :src="getImageUrl('welcome.jpg')" id="hero" class="mb-6">
        <v-row class="fill-height px-3" align="center" justify="center">
            <v-col class="text-center text-white">
                <h1 class="font-weight-bold text-h4 text-sm-h3 text-md-h2 text-lg-h1 mb-4">
                    Arknights Toolkit
                </h1>
                <p class="text-h7 text-sm-h6 text-md-h5 text-lg-h4">
                    Plan your farming. Stay updated. Connect with others.
                </p>
            </v-col>
        </v-row>
    </v-img>
</template>

<script>
export default {
    name: 'HeroBanner',
    methods: {
        getImageUrl(name) {
            return new URL(`/src/assets/${name}`, import.meta.url).href
        }
    }
}
</script>

<style scoped>
/* Darken the hero image */
#hero :deep(img) {
    filter: brightness(50%);
}
</style>