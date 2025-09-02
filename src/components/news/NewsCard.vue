<template>
    <RouterLink
        :to="`/community/news/${id}`"
        class="text-decoration-none"
    >
    <v-card class="mb-4" hover v-ripple>
            <v-card-title>
                {{ title }}
            </v-card-title>
            <v-card-subtitle>
                {{ date }} | {{ category }}
            </v-card-subtitle>
            <v-card-text>
                {{ summary }}
            </v-card-text>
        </v-card>
    </RouterLink>
</template>

<script>
export default {
    name: 'NewsCard',
    props: {
        id: Number,
        title: String,
        date: String,
        category: String,
        summary: String
    }
}
</script>