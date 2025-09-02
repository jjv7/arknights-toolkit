<template>
    <v-app>
        <AppNavbar />
        <v-main class="d-flex flex-column" min-height="100vh">
            <div class="flex-grow-1">
                <RouterView />
            </div>
        </v-main>
        <AppFooter />
    </v-app>
</template>

<script>
import AppNavbar from './components/AppNavbar.vue'
import AppFooter from './components/AppFooter.vue'

export default {
    name: 'App',
    components: {
        AppNavbar,
        AppFooter
    }
}
</script>
