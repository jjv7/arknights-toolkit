<template>
    <h2 class="text-h4 font-weight-bold text-center mb-4">Create New Post</h2>

    <!-- Show login prompt if user is not logged in -->
    <v-alert v-if="!user.isAuthenticated" type="error" dense outlined class="my-4">
        <v-row align="center" justify="space-between" class="ma-0">
            <v-col class="text-break pa-0" cols="8" sm="auto">
                Must be logged in to create posts.
            </v-col>
            <v-col class="pa-0" cols="4" sm="auto">
                <v-btn color="success" to="/login">Login</v-btn>
            </v-col>
        </v-row>
    </v-alert>
    
    <v-card v-else class="mx-auto px-3 py-4" max-width="90%">
        <v-form ref="form">
            <v-card-text>
                <v-text-field
                    v-model="title"
                    :rules="titleRules"
                    label="Post Title"
                    required
                    clearable
                    class="mb-4"
                />

                <v-textarea
                    v-model="content"
                    :rules="contentRules"
                    label="Post Content"
                    auto-grow
                    clearable
                    class="mb-4"
                />

                <v-select
                    v-model="category"
                    :rules="categoryRules"
                    :items="categories"
                    label="Select Category"
                    required
                    class="mb-4"
                    clearable
                />
                
                <div class="mb-4">
                    <v-btn
                        color="primary"
                        @click="createPost"
                        :disabled="!title?.trim() || !content?.trim() || !category"
                    >
                        Post
                    </v-btn>
                    <v-btn to="/community/social" color="red" class="ml-4">Cancel</v-btn>
                </div>
                
                <v-alert v-if="postError" type="error" dense outlined class="mt-4">
                    {{ postError }}
                </v-alert>
                <v-alert v-if="postSuccess" type="success" dense outlined class="mt-4">
                    {{ postSuccess }}
                </v-alert>
            </v-card-text>
        </v-form>
    </v-card>
</template>

<script>
import axios from 'axios'
import { useUserStore } from '../../stores/user'

export default {
    name: 'CreatePost',
    setup() {
        const user = useUserStore();

        return {
            user
        }
    },
    data() {
        return {
            title: "",
            content: "",
            category: "",
            categories: ["General", "Strategy", "Guide", "Lore"],
            postError: "",
            postSuccess: "",
            titleRules: [
                v => !!v || "Title is required",
                v => v.length <= 60 || "Title must be 60 characters or less"
            ],
            contentRules: [
                v => !!v || "Content is required",
                v => v.length <= 1000 || "Content must be 1000 characters or less"
            ],
            categoryRules: [
                v => !!v || "Category is required"
            ]
        };
    },
    methods: {
        async createPost() {
            const form = await this.$refs.form.validate();
            if (!form.valid) return;

            this.postError = "";
            this.postSuccess = "";

            const postData = {
                author: this.user.username,
                title: this.title,
                content: this.content,
                category: this.category
            };

            // Authentication check just in case
            if (!this.user.isAuthenticated) {
                this.postError = "Error: User is not logged in";
                return
            }

            try {
                const response = await axios.post(
                    '/apis/api_posts.php',
                    postData
                );

                const payload = response.data;

                if (payload.success) {
                    this.postSuccess = "Post created successfully!";
                    setTimeout(() => this.$router.push('/community/social'), 2000);
                } else {
                    this.postError = response.data.message || "Failed to create post.";
                }
            } catch (err) {
                this.postError = err.response?.data?.message || "Network or server error.";
            }   
        }
    }
};
</script>
