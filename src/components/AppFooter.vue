<template>
    <v-divider />
    <v-footer class="d-flex flex-column pb-6">
        <div class="d-flex w-100 align-center px-4 pt-2 pb-4">
            Disclaimer: Arknights Toolkit is not affiliated with, endorsed by, or associated with Hypergryph,
            Studio Montagne, or Yostar. All trademarks and content belong to their respective owners.
            <div class="d-flex ga-2 ms-auto pl-8">
                <v-btn @click="toggleTheme" block>
                    <v-icon>
                        {{ currentTheme === 'dark' ? 'mdi-weather-sunny' : 'mdi-weather-night' }}
                    </v-icon>
                </v-btn>
            </div>
        </div>
        <div class="px-4 py-2 bg-surface-variant text-center w-100 rounded-lg">
            © {{ new Date().getFullYear() }} - Arknights Toolkit by Jayden Kong
        </div>
    </v-footer>
</template>

<script>
import { useTheme } from 'vuetify'

export default {
    name: 'AppFooter',
    setup() {
        const theme = useTheme()

        return {
            theme
        }
    },
    computed: {
        currentTheme() {
            return this.theme.global.name.value
        }
    },
    methods: {
        toggleTheme() {
            this.theme.global.name.value = this.theme.global.name.value === 'dark' ? 'light' : 'dark';
        }
    }
}
</script>