<template>
    <v-card class="mx-auto px-4 py-4" max-width="475px">
        <v-img
            :src="getImageUrl(selectedAmiya)"
            :alt="imgAlt"
            max-width="450px"
            max-height="370px"
        />
    </v-card>
    <v-container>
        <h4 class="text-subtitle-1 text-medium-emphasis text-center mb-2">Pick your Amiya:</h4>
        <v-row justify="center">
            <v-col cols="auto">
                <v-radio-group
                    v-model="selectedAmiya"
                    inline
                >
                    <v-radio label="Amiya Pog" value="amiyapog.png" class="pr-4" />
                    <v-radio label="Amiya Fumo" value="amiyafumo.png" />
                </v-radio-group>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: 'AmiyaSelector',
    data() {
        return {
            selectedAmiya: "amiyapog.png"
        }
    },
    methods: {
        getImageUrl(name) {
            return new URL(`/src/assets/${name}`, import.meta.url).href
        }
    },
    computed: {
        imgAlt() {
            const name = this.selectedAmiya
                .replace(/\.\w+$/, '')      // Remove file extension
            return `Image of ${name}`;
        }
    }
}
</script>