<template>
    <div class="article-container mx-auto">
        <v-btn to="/community/news" class="mb-4" prepend-icon="mdi-arrow-left">
            Back to News Search
        </v-btn>
    
        <div v-if="article">
            <h1 class="text-h4 font-weight-bold mb-2">{{ article.title }}</h1>
            <p class="text-subtitle-1 text-medium-emphasis mb-4">{{ article.date }} | {{ article.category }}</p>
            <v-divider class="mb-4" />
            <p class="text-body-1">{{ article.content }}</p>
        </div>
        <div v-else>
            <p>Article not found.</p>
        </div>
    </div>
</template>

<script>
import news from '../../data/news.json'

export default {
    name: 'NewsArticle',
    props: ['id'],
    computed: {
        article() {
            return news.find(n => n.id === Number(this.id));
        }
    }
}
</script>

<style scoped>
.article-container {
    max-width: 90%;
}
</style>