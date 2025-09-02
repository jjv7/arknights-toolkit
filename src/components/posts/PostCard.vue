<template>
    <RouterLink :to="`/community/social/posts/${id}`" class="text-decoration-none">
        <v-card class="mb-4" hover v-ripple>
            <v-card-title>
                <div class="d-flex align-center">
                    <v-icon small class="mr-2" color="primary">mdi-account</v-icon>
                    {{ author }}
                </div>
            </v-card-title>
            <v-card-subtitle>
                {{ formatDate(timestamp) }} | {{ category }} | Likes: {{ likeCount }}
            </v-card-subtitle>
            <v-card-text>{{ title }}</v-card-text>
        </v-card>
    </RouterLink>
</template>

<script>
export default {
    name: "PostCard",
    props: {
        id: Number,
        author: String,
        title: String,
        timestamp: String,
        category: String,
        likeCount: Number
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
        }
    }
}
</script>