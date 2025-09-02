<template>
    <v-app-bar fixed app>
        <v-app-bar-nav-icon class="d-md-none" @click="toggleDrawer" />
        <RouterLink to="/" class="d-flex align-center text-decoration-none mr-4" :class="themeTextColour">
            <v-img src="/ak-toolkit/arknights-toolkit-icon.svg" alt="arknights icon" width="40" height="40" class="mx-2" />
            <span class="text-h6 font-weight-bold">Arknights Toolkit</span>
        </RouterLink>

        <div class="d-none d-md-flex align-center">
            <v-btn to="/" class="mx-3">Home</v-btn>
            <v-btn to="/community" class="mr-3">Community</v-btn>
            <v-btn to="/drops" class="mr-3">Drops</v-btn>
            <v-btn to="/about" class="mr-3">About</v-btn>
        </div>

        <v-spacer />

        <div v-if="user.isAuthenticated" class="d-none d-md-flex align-center mr-3">
            <v-menu>
                <template #activator="{ props }">
                    <v-btn v-bind="props" text>
                        <v-icon>mdi-account-circle</v-icon>
                        {{ user.username }}
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item @click="handleLogout">
                        <v-icon>mdi-logout</v-icon>
                        Logout
                    </v-list-item>
                </v-list>
            </v-menu>
        </div>
        <v-btn v-else to="/login" class="d-none d-md-flex mr-3">
            <v-icon start>mdi-login</v-icon>
            Login
        </v-btn>
        
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" temporary app class="d-md-none">
        <v-list>
            <div v-if="user.isAuthenticated" class="text-center py-4">
                <v-icon size="48" class="mb-2">mdi-account-circle</v-icon>
                <div class="text-subtitle-1 font-weight-bold">{{ user.username }}</div>
            </div>
            <v-list-item to="/" @click="hideDrawer">
                <v-list-item-title>Home</v-list-item-title>
            </v-list-item>
            <v-list-item to="/community" @click="hideDrawer">
                <v-list-item-title>Community</v-list-item-title>
            </v-list-item>
            <v-list-item to="/drops" @click="hideDrawer">
                <v-list-item-title>Drops</v-list-item-title>
            </v-list-item>
            <v-list-item to="/about" @click="hideDrawer">
                <v-list-item-title>About</v-list-item-title>
            </v-list-item>
        </v-list>
        
        <div class="pa-4">
            <v-btn v-if="!user.isAuthenticated" to="/login" @click="hideDrawer" block>
                <v-icon start>mdi-login</v-icon>
                Login
            </v-btn>
            <v-btn v-else @click="handleLogout" block>
                <v-icon start>mdi-logout</v-icon>
                Logout
            </v-btn>
        </div>
    </v-navigation-drawer>
</template>

<script>
import { useTheme } from 'vuetify'
import { useUserStore } from '../stores/user'

export default {
    name: 'AppNavbar',
    setup() {
        const user = useUserStore();

        return {
            user
        }
    },
    data() {
        return {
            drawer: false,
        }
    },
    methods: {
        toggleDrawer() {
            this.drawer = !this.drawer;
        },
        hideDrawer() {
            this.drawer = false
        },
        handleLogout() {
            this.user.clearUser();
            this.hideDrawer();
        }
    },
    computed: {
        themeTextColour() {     // RouteLink will appear blue for links otherwise
            const theme = useTheme()
            return theme.global.current.value.dark ? 'text-white' : 'text-black';
        }
    }
}
</script>