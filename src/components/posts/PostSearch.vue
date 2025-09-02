<template>
    <v-row>
        <v-col cols="12" md="4" lg="3" class="mb-4">
            <v-card class="pa-4">
                <v-card-title>Post Filters</v-card-title>

                <v-text-field
                    v-model="filters.title"
                    label="Search Title"
                    prepend-inner-icon="mdi-magnify"
                    clearable
                />

                <v-text-field
                    v-model="filters.author"
                    label="Search Author"
                    prepend-inner-icon="mdi-account"
                    clearable
                />

                <v-select
                    v-model="filters.category"
                    label="Category"
                    prepend-inner-icon="mdi-filter-variant"
                    :items="categories"
                />

                <v-select
                    v-model="filters.sortOrder"
                    label="Sort by"
                    prepend-inner-icon="mdi-sort"
                    :items="['Newest first', 'Oldest first', 'Most likes', 'Least likes']"
                />
            </v-card>
        </v-col>

        
        <v-col cols="12" md="8" lg="9">
            <!-- Show login prompt if user is not logged in -->
            <v-alert v-if="!user.isAuthenticated" type="error" dense outlined class="my-4">
                <v-row align="center" justify="space-between" class="ma-0">
                    <v-col class="text-break pa-0" cols="8" sm="auto">
                        Must be logged in to create a post.
                    </v-col>
                    <v-col class="pa-0" cols="4" sm="auto">
                        <v-btn color="success" to="/login">Login</v-btn>
                    </v-col>
                </v-row>
            </v-alert>
            <div v-else>
                <v-btn color="primary" class="mb-4" to="/community/social/create" prepend-icon="mdi-plus">
                    Create New Post
                </v-btn>
                <PostCard
                    v-for="post in paginatedPosts"
                    :key="post.id"
                    :id="post.id"
                    :author="post.author"
                    :title="post.title"
                    :timestamp="post.timestamp"
                    :category="post.category"
                    :likeCount="post.like_count"
                />
                <v-alert type="info" v-if="!filteredPosts.length">
                    No posts found.
                </v-alert>
            </div>
        </v-col>
    </v-row>

    <v-pagination
        v-model="currentPage"
        :length="pageCount"
        :density="paginationDensity"
        :total-visible="paginationVisible"
        class="mt-4"
    />
</template>

<script>
import axios from 'axios'
import PostCard from './PostCard.vue'
import { useUserStore } from '../../stores/user';

export default {
    name: 'SocialPage',
    components: { PostCard },
    setup() {
        const user = useUserStore();

        return {
            user
        }
    },
    data() {
        return {
            posts: [],
            currentPage: 1,
            itemsPerPage: 5,
            filters: {
                title: "",
                author: "",
                category: "All",
                sortOrder: "Newest first",
            },
            categories: ["All", "General", "Strategy", "Guide", "Lore"],
        }
    },
    computed: {
        filteredPosts() {
            // Post filters
            let filtered = this.posts.filter(post => {
                const matchTitle = !this.filters.title || post.title.toLowerCase().match(this.filters.title.toLowerCase());
                const matchAuthor = !this.filters.author || post.author.toLowerCase().match(this.filters.author.toLowerCase());
                const matchCategory = this.filters.category === 'All' || post.category === this.filters.category;
                return matchTitle && matchAuthor && matchCategory;
            });

            // Sort the filtered posts based on sort order mode
            switch (this.filters.sortOrder) {
                case "Oldest first":
                    filtered.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));
                    break;
                case "Most likes":
                    filtered.sort((a, b) => b.like_count - a.like_count);
                    break;
                case "Least likes":
                    filtered.sort((a, b) => a.like_count - b.like_count);
                    break;
                case "Newest first":    // Default to newest first
                default:
                    filtered.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
            }

            return filtered;
        },
        paginatedPosts() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredPosts.slice(start, start + this.itemsPerPage);
        },
        pageCount() {
            return Math.ceil(this.filteredPosts.length / this.itemsPerPage);
        },
        paginationDensity() {
            const display = this.$vuetify.display;
            return display.xs ? "comfortable" : "default";
        },
        paginationVisible() {
            const display = this.$vuetify.display;
            return display.xs ? undefined : 10;
        }
    },
    async mounted() {
        try {
            const response = await axios.get('/apis/api_posts.php');
            this.posts = response.data;
        } catch (err) {
            console.error("Failed to fetch posts:", err);
        }
    }
}
</script>
