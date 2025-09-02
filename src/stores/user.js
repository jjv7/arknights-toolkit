import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => ({
        isAuthenticated: false,
        username: "",
    }),
    actions: {
        setUser(username) {
            this.isAuthenticated = true;
            this.username = username;
        },
        clearUser() {
            this.isAuthenticated = false;
            this.username = "";
        }
    }
});