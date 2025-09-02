<template>
    <h2 class="text-h4 font-weight-bold text-center mb-4">Edit Post</h2>

    <!-- Show error if current user is not the author -->
    <v-alert v-if="user.username !== this.author" type="error" dense outlined class="my-4">
        You are not authorised to edit this post.
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
                        @click="submitEdit"
                        :disabled="!title?.trim() || !content?.trim() || !category"
                    >
                        Save Changes
                    </v-btn>
                    <v-btn to="/community/social" color="red" class="ml-4">Cancel</v-btn>
                </div>

                <v-alert v-if="editError" type="error" dense outlined class="mt-4">
                    {{ editError }}
                </v-alert>
                <v-alert v-if="editSuccess" type="success" dense outlined class="mt-4">
                    {{ editSuccess }}
                </v-alert>
            </v-card-text>
        </v-form>
    </v-card>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '../../stores/user';

export default {
    name: 'EditPost',
    props: ['id'],
    setup() {
        const user = useUserStore();
        
        return {
            user
        };
    },
    data() {
        return {
            title: "",
            author: "",
            content: "",
            category: "",
            timestamp: "",
            categories: ["General", "Strategy", "Guide", "Lore"],
            editError: "",
            editSuccess: "",
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
    async created() {
        try {
            const response = await axios.get(`/apis/api_posts.php?id=${this.id}`);
            if (response.data.success) {
                const post = response.data.post;
                this.title = post.title;
                this.content = post.content;
                this.category = post.category;
                this.timestamp = post.timestamp;
                this.author = post.author;
            } else {
                this.editError = 'Post not found.';
            }
        } catch (err) {
            this.editError = 'Failed to load post.';
        }
    },
    methods: {
        async submitEdit() {
            const form = await this.$refs.form.validate();
            if (!form.valid) return;

            try {
                const payload = {
                    id: this.id,
                    title: this.title,
                    content: this.content,
                    category: this.category,
                    timestamp: this.timestamp
                };

                const response = await axios.put(
                    '/apis/api_posts.php',
                    payload
                );

                if (response.data.success) {
                    this.editSuccess = 'Post updated successfully!';
                    setTimeout(() => this.$router.push(`/community/social/posts/${this.id}`), 2000);
                } else {
                    this.editError = response.data.message || 'Update failed.';
                }
            } catch (err) {
                this.editError = err.response?.data?.message || 'Failed to update post.';
            }
        }
    }
};
</script>
