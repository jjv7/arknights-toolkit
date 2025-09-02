<template>
    <v-container class="mx-auto mb-4">
        <v-card class="mb-8 pa-8">
            <h1 class="text-center mb-4">Why Doctors Trust Us</h1>
            <v-row justify="center">
                <v-col v-for="(reason, index) in reasons" :key="index" cols="12" md="6" lg="4">
                    <ReasonCard :title="reason.title" :description="reason.description" />
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
import ReasonCard from './ReasonCard.vue';
import reasons from '../../data/reasons.json'

export default {
    name: 'TrustReasons',
    components: {
        ReasonCard
    },
    data() {
        return {
            reasons
        }
    }
}
</script>