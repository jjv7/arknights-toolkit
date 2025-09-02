<template>
    <v-container>
        <h1 class="text-h2 font-weight-bold text-center mb-4">User Login</h1>
        <v-divider class="mb-6" />
        <v-card class="mx-auto px-6 py-8" max-width="90%">
            <v-form ref="form" class="px-4">
                <v-text-field
                    v-model="username"
                    :rules="usernameRules"
                    label="Username"
                    required
                    clearable
                    class="mb-4"
                />
                <v-text-field
                    v-model="password"
                    :rules="passwordRules"
                    label="Password"
                    type="password"
                    required
                    clearable
                    class="mb-4"
                />
                <v-btn color="green" @click="handleLogin" class="mb-4">Login</v-btn>
                <v-alert
                    v-if="loginError"
                    type="error"
                    class=""
                    dense
                    outlined
                >
                    {{ loginError }}
                </v-alert>
            </v-form>
            
            <v-card-text>
                Don't have an account? <RouterLink to="/signup">Sign up</RouterLink>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '../stores/user'

export default {
    name: 'Login',
    setup() {
        const user = useUserStore();
        const router = useRouter();

        return {
            user,
            router
        }
    },
    data() {
        return {
            username: "",
            password: "",
            loginError: "",
            usernameRules: [
                v => !!v || "Username is required"
            ],
            passwordRules: [
                v => !!v || "Password is required"
            ]
        }
    },
    methods: {
        async handleLogin() {
            this.loginError = "";

            const form = await this.$refs.form.validate();
            if (!form.valid) return;

            try {
                const response = await axios.post(
                    '/apis/api_user.php',
                    { username: this.username, password: this.password }
                );
                
                const payload = response.data;

                if (payload.success) {
                    this.user.setUser(this.username);
                    this.router.push('/');
                } else {
                    this.loginError = "Incorrect username or password"
                }
            } catch(err) {
                if (err.response) {
                    this.loginError = err.response.data?.message || "Login failed.";
                } else {
                    this.loginError = "Network or server error.";
                }
            }
        }
    }
}
</script>