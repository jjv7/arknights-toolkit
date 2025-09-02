<template>
    <v-container>
        <h1 class="text-h2 font-weight-bold text-center mb-4">User Registration</h1>
        <v-divider class="mb-6" />
        <v-card class="mx-auto px-6 py-8" max-width="90%">
            <v-form ref="form" class="px-4">
                <v-subheader class="text-h5">Account</v-subheader>
                <v-row class="mt-1">
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="firstName"
                            :rules="nameRules"
                            label="First Name"
                            required
                            clearable
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="lastName"
                            :rules="nameRules"
                            label="Last Name"
                            required
                            clearable
                        />    
                    </v-col>
                </v-row>

                <v-subheader class="text-h5">Email</v-subheader>
                <v-row class="mt-1">
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="email"
                            :rules="emailRules"
                            label="Email"
                            required
                            clearable
                        />
                    </v-col>
                </v-row>

                <v-subheader class="text-h5">Account</v-subheader>
                <v-row class="mb-4 mt-1">
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="username"
                            :rules="usernameRules"
                            label="Username"
                            required
                            clearable
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="password"
                            :rules="passwordRules"
                            label="Password"
                            type="password"
                            required
                            clearable
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="confirmPassword"
                            :rules="confirmPasswordRules"
                            label="Confirm password"
                            type="password"
                            required
                            clearable
                        />
                    </v-col>
                </v-row>
                

                <v-btn color="blue" @click="handleSignup" class="mb-4">
                    Sign Up
                </v-btn>

                <v-alert v-if="signupError" type="error" dense outlined>
                    {{ signupError }}
                </v-alert>
                <v-alert v-if="signupSuccess" type="success" dense outlined>
                    {{ signupSuccess }}
                </v-alert>
            </v-form>
            <v-card-text>
                Already have an account? <RouterLink to="/login">Login</RouterLink>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios'
import { useRouter } from 'vue-router'

export default {
    name: 'SignUp',
    setup() {
        const router = useRouter();
        return {
            router
        }
    },
    data() {
        return {
            firstName: "",
            lastName: "",
            email: "",
            username: "",
            password: "",
            confirmPassword: "",
            signupError: "",
            signupSuccess: "",
            nameRules: [
                v => !!v || "Name is required",
                v => v.length <= 50 || "Name must be 50 characters or less",
                v => /^[A-Za-z]+$/.test(v) || "Name may only contain letters"
            ],
            emailRules: [
                v => !!v || "Email is required",
                v => {
                    // RegEx taken from https://v2.vuejs.org/v2/cookbook/form-validation.html
                    let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(v) || "Email must be valid";
                }
            ],
            usernameRules: [
                v => !!v || "Username is required",
                v => v.length >= 3 || "Username must be at least 3 characters"
            ],
            passwordRules: [
                v => !!v || "Password is required",
                v => v.length >= 8 || "Password must be at least 8 characters",
                v => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(v) || "Password must include at least one special character"
            ],
            confirmPasswordRules: [
                v => !!v || "Please confirm your password",
                v => v === this.password || "Passwords do not match"
            ]
        }
    },
    methods: {
        async handleSignup() {
            const form = await this.$refs.form.validate();
            if (!form.valid) return;

            this.signupError = "";
            this.signupSuccess = "";

            const signupData = {
                first_name: this.firstName,
                last_name: this.lastName,
                email: this.email,
                username: this.username,
                password: this.password
            };

            try {
                const response = await axios.post(
                    '/apis/api_signup.php',
                    signupData
                );

                const payload = response.data;

                if (payload.success) {
                    this.signupSuccess = payload.message;
                    setTimeout(() => this.router.push('/login'), 2000);
                } else {
                    this.signupError = payload.message || 'Signup failed.';
                }
            } catch (err) {
                this.signupError = err.response?.data?.message || 'Network or server error.';
            }
        }
    }
}
</script>