<template>
    <div class="post-container mx-auto">
        <div class="d-flex flex-column flex-sm-row justify-space-between mb-4">
            <v-btn to="/community/social" class="mb-2" prepend-icon="mdi-arrow-left">
                Back to Social Feed
            </v-btn>

            <div class="d-flex flex-column flex-sm-row">
                <!-- Edit button, only shown if the authenticated user is the author -->
                <v-btn
                    v-if="user.username === post.author"
                    color="orange-darken-1"
                    prepend-icon="mdi-pencil"
                    :to="`/community/social/posts/edit/${id}`"
                    class="mb-2 mr-sm-2"
                >
                    Edit
                </v-btn>

                <!-- Delete button, only shown if the authenticated user is the author or the admin -->
                <v-btn
                    v-if="user.username === post.author || user.username === 'admin'"
                    color="red"
                    prepend-icon="mdi-delete"
                    @click="showDeleteDialog = true"
                    class="mb-2"
                >
                    Delete
                </v-btn>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <v-dialog v-model="showDeleteDialog" max-width="400px">
            <v-card class="px-2 py-3">
                <v-card-title class="text-h6">Confirm Deletion</v-card-title>
                <v-card-text>Are you sure you want to delete this post?</v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="showDeleteDialog = false">Cancel</v-btn>
                    <v-btn color="red" @click="handleDelete">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Show login prompt if user is not logged in -->
        <v-alert v-if="!user.isAuthenticated" type="error" dense outlined class="my-4">
            <v-row align="center" justify="space-between" class="ma-0">
                <v-col class="text-break pa-0" cols="8" sm="auto">
                    Must be logged in to view posts.
                </v-col>
                <v-col class="pa-0" cols="4" sm="auto">
                    <v-btn color="success" to="/login">Login</v-btn>
                </v-col>
            </v-row>
        </v-alert>
        <div v-else>
            <!-- Show errors associated with fetching posts -->
            <div v-if="error">
                <v-alert type="error">{{ error }}</v-alert>
            </div>
            <!-- Display all post content -->
            <div v-else-if="post">
                <h1 class="text-h4 font-weight-bold mb-2">{{ post.title }}</h1>
                <p class="text-subtitle-1 text-medium-emphasis mb-2">
                    {{ formatDate(post.timestamp) }} | {{ post.category }}
                </p>
                <div class="d-flex align-center mb-2 text-medium-emphasis">
                    <v-icon small class="mr-2">mdi-account</v-icon>
                    {{ post.author }}
                </div>
                <v-btn 
                    :color="userLiked ? 'pink' : 'grey'"
                    @click="toggleLike"
                    prepend-icon="mdi-heart"
                    class="mb-4"
                >
                    {{ post.like_count }}
                </v-btn>
                <v-divider class="mb-4" />
                <p class="text-body-1">{{ post.content }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '../../stores/user'

export default {
    name: 'PostPage',
    props: ['id'],
    setup() {
        const user = useUserStore();

        return {
            user
        }
    },
    data() {
        return {
            post: null,
            userLiked: false,
            error: "",
            showDeleteDialog: false
        };
    },
    async created() {
        try {
            const response = await axios.get(`/apis/api_posts.php?id=${this.id}`);

            if (response.data.success) {
                this.post = response.data.post;
                
                // See if user has liked the post already
                const likeStatus = await axios.get(`/apis/api_posts.php?like_status=true&post_id=${this.id}&user=${this.user.username}`);
                this.userLiked = likeStatus.data.liked;
            } else {
                this.error = 'Post not found';
            }
        } catch (err) {
            this.error = 'Failed to load post';
        }
    },
    methods: {
        formatDate(dateStr) {
            // Convert string received from database to ISO format
            const utcString = dateStr.includes('T') ? dateStr : dateStr.replace(' ', 'T') + 'Z';
            const date = new Date(utcString);
            
            // Format date to output dd/mm/yy, hh:mm AM/PM
            const options = {
                day: "2-digit",
                month: "2-digit",
                year: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true
            };
            return date.toLocaleString(undefined, options);
        },
        async handleDelete() {
            try {
                const response = await axios.delete(
                    '/apis/api_posts.php',
                    {
                        data: {
                            id: this.id
                        }
                    }
                );

                if (response.data.success) {
                    this.showDeleteDialog = false;          // Hide modal
                    this.$router.push('/community/social'); // Go back to post search
                } else {
                    this.error = response.data.message || "Failed to delete post";
                    this.showDeleteDialog = false;
                }
            } catch (err) {
                this.error = "Failed to delete post";
                this.showDeleteDialog = false;
            }
        },
        async toggleLike() {
            try {
                const response = await axios.put(
                    `/apis/api_posts.php?like=true`,
                    { id: this.post.id, user: this.user.username }  // Likes stored on database as username and associated post id
                );

                const payload = response.data;

                if (payload.success) {
                    this.userLiked = payload.liked;
                    this.post.like_count += payload.liked ? 1 : -1; // Update loaded payload count
                }
            } catch (err) {
                console.error("Error toggling like:", err);
            }
        }
    }
};
</script>

<style scoped>
.post-container {
    max-width: 90%;
}
</style>