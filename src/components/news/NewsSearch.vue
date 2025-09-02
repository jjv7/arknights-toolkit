<template>
    <v-row>
        <v-col cols="12" md="4" lg="3" class="mb-4">
            <v-card class="pa-4">
                <v-card-title>News Filters</v-card-title>
                <v-date-input
                    v-model="filters.startDate"
                    label="Start date"
                    prepend-icon=""
                    prepend-inner-icon="$calendar"
                    placeholder="yyyy-mm-dd"
                    :input-format="'yyyy-mm-dd'"
                    :display-format="formatDate"
                />
                <v-date-input
                    v-model="filters.endDate"
                    label="End date"
                    prepend-icon=""
                    prepend-inner-icon="$calendar"
                    placeholder="yyyy-mm-dd"
                    :input-format="'yyyy-mm-dd'"
                    :display-format="formatDate"
                />
                <v-text-field
                    v-model="filters.title"
                    label="Search Title"
                    prepend-inner-icon="mdi-magnify"
                />
                <v-text-field
                    v-model="filters.summary"
                    label="Search Content"
                    prepend-inner-icon="mdi-magnify"
                />
                <v-select
                    v-model="filters.category"
                    label="Category"
                    prepend-inner-icon="mdi-filter-variant"
                    :items="categories"
                    :menu-props="{scrollStrategy: 'close'}"
                />
            </v-card>
        </v-col>

        <!-- News List -->
        <v-col cols="12" md="8" lg="9">
            <NewsCard
                v-for="(news, index) in paginatedNews"
                :key="index"
                :id="news.id"
                :title="news.title"
                :date="news.date"
                :category="news.category"
                :summary="news.summary"
            />
            <v-alert type="info" v-if="!filteredNews.length">
                No news found.
            </v-alert>
        </v-col>
    </v-row>
    <v-pagination
        v-model="currentPage"
        :length="pageCount"
        :density="paginationDensity"
        :total-visible="paginationVisible"
    />
</template>

<script>
import news from '../../data/news.json'
import NewsCard from './NewsCard.vue'
import { useDate } from 'vuetify'
import { VDateInput } from 'vuetify/labs/VDateInput'

export default {
    name: 'NewsSearch',
    components: {
        VDateInput,
        NewsCard
    },
    data() {
        return {
            news,
            currentPage: 1,
            itemsPerPage: 5,
            filters: {
                startDate: "",
                endDate: "",
                title: "",
                summary: "",
                category: "All"
            }
        }
    },
    methods: {
        formatDate(date) {  // Force date to be in ISO format
            return useDate().toISO(date);
        }
    },
    computed: {
        filteredNews() {
            return this.news.filter(n => {
                // Date range filter
                const newsDate = new Date(n.date);
                const start = this.filters.startDate ? new Date(this.filters.startDate) : "";
                const end = this.filters.endDate ? new Date(this.filters.endDate) : "";

                // Normalise time boundaries
                if (start) start.setHours(0, 0, 0, 0);
                if (end) end.setHours(23, 59, 59, 999);

                const matchDate = (!start || newsDate >= start) && (!end || newsDate <= end);

                // Title filter
                const matchTitle = n.title.toLowerCase().match(this.filters.title.toLowerCase());

                // Content filter
                const matchContent = n.summary.toLowerCase().match(this.filters.summary.toLowerCase());
                
                // Category filter
                const matchCategory = this.filters.category === "All" ||
                                      n.category.toLowerCase().match(this.filters.category.toLowerCase());

                return matchDate && matchTitle && matchContent && matchCategory;
            }).sort((a, b) => new Date(b.date) - new Date(a.date)); // Sort by latest date first
        },
        paginatedNews() {   // Figure out the section of filtered news to display
            const current = this.currentPage * this.itemsPerPage;
            const start = current - this.itemsPerPage;
            return this.filteredNews.slice(start, current);
        },
        pageCount() {
            return Math.ceil(this.filteredNews.length / this.itemsPerPage);
        },
        paginationDensity() {    // To ensure the pagination element does not overflow on small screens
            const display = this.$vuetify.display;
            if (display.xs) return "comfortable";
            else return "default";
        },
        paginationVisible() {    // Display max 10 when not an xs display, else let vuetify decide
            const display = this.$vuetify.display;
            if (display.xs) return undefined;
            else return "10";
        },
        categories() {  // Obtain unique categories from news
            const uniqueCategories = [...new Set(this.news.map(item => item.category))].sort();
            return ["All", ...uniqueCategories];    // Append all to the start
        }
    }
}
</script>