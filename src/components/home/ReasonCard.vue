<template>
    <v-card class="mx-auto fill-height" align="center" variant="outlined">
        <v-card-title class="font-weight-bold text-pre-wrap">
            {{ title }}
        </v-card-title>
        <v-card-text>{{ description }}</v-card-text>
    </v-card>
</template>

<script>
export default {
    name: 'ReasonCard',
    props: {
        title: String,
        description: String
    }
}
</script>