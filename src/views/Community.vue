<template>
    <v-container>
        <h1 class="text-h2 font-weight-bold text-center mb-4">Community</h1>
        <v-divider />

        <!-- Use tabs to separate news and posts section -->
        <v-tabs v-model="tab" grow background-color="primary" dark class="mb-8">
            <v-tab to="/community/news">
                News
            </v-tab>
            <v-tab to="/community/social">
                Posts
            </v-tab>
        </v-tabs>

        <router-view />
    </v-container>
</template>

<script>
export default {
    name: 'Community',
    data() {
        return {
            tab: null
        }
    }
}
</script>
