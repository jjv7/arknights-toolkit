<template>
    <v-container>
        <h1 class="text-h2 font-weight-bold text-center mb-4">Material Drop Rates</h1>
        <v-divider class="mb-6" />

        <v-container max-width="1500px">
            <p class="text-caption text-medium-emphasis mb-1">
                * Event materials are not searchable.
            </p>
            
            <v-autocomplete
                v-model="selectedItemName"
                :items="itemNames"
                prepend-inner-icon="mdi-magnify"
                label="Select Material"
                auto-select-first
                clearable
            />
        </v-container>

        <div v-if="selectedItemName" class="mb-4">
            <h2 class="text-h5 mb-2">
                Drop Rates for "{{ selectedItemName }}"
            </h2>
            <p class="text-caption text-medium-emphasis pl-1">
                (Data source: {{ dataSource }})
            </p>
        </div>

        <v-data-table
            v-if="selectedItemName"
            :headers="tableHeaders"
            :items="tableDropData"
            :loading="loading"
            loading-text="Fetching drop rates... Please wait"
            class="rounded mb-8"
        >
            <!-- If this in items, it will sort lexicographically -->
            <!-- Uses v-slot -->
            <template #item.dropRate="{ item }">
                {{ item.dropRate }}%
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
import axios from 'axios';
import itemMap from '../data/itemmap.json';
import stageMap from '../data/stagemap.json';

const DEFAULT_SANITY = 1;
const API_TIMEOUT = 5000;
const FALLBACK_TIMEOUT = 3000;
const DATA_SOURCE_UNKNOWN = "resolving...";
const DATA_SOURCE_API = "penguin-stats.io";
const DATA_SOURCE_FALLBACK = "backup matrix";

export default {
    name: 'Drops',
    data() {
        return {
            itemMap,
            stageMap,
            selectedItemName: "",
            dropData: [],
            dataSource: DATA_SOURCE_UNKNOWN,
            tableHeaders: [
                { title: 'Stage', align: 'start', key: 'stage' },
                { title: 'Loots', align: 'end', key: 'quantity' },
                { title: 'Samples', align: 'end', key: 'times' },
                { title: 'Sanity Cost', align: 'end', key: 'sanity' },
                { title: 'Drop Rate', align: 'end', key: 'dropRate' },
                { title: 'Sanity / Drop', align: 'end', key: 'efficiency' }
            ],
            loading: false
        };
    },
    computed: {
        itemNames() {   // Reads in every material name from the map file
            return this.itemMap.map(item => item.name).sort();
        },
        itemNameToId() {    // Obtains each material and its ID
            return Object.fromEntries(this.itemMap.map(item => [item.name, item.itemId]));
        },
        selectedItemId() {  // Watched property to initiate data fetching
            return this.itemNameToId[this.selectedItemName];
        },
        stageIdToCode() {   // Obtains each stage's ID and its code
            return Object.fromEntries(this.stageMap.map(stage => [stage.stageId, stage.code]));
        },
        stageIdToSanity() { // Obtains each stage and its sanity
            return Object.fromEntries(this.stageMap.map(stage => [stage.stageId, stage.apCost]));
        },
        tableDropData() {
            return this.dropData.map(drop => {
                const times = drop.times || 0;
                const quantity = drop.quantity || 0;
                const dropRate = times > 0 ? (quantity / times) * 100 : 0;
                const sanity = this.stageIdToSanity[drop.stageId] || DEFAULT_SANITY;
                const efficiencyValue = quantity > 0 ? (times / quantity) * sanity : Infinity;

                return {
                    stage: this.resolveStageCode(drop.stageId),
                    quantity,
                    times,
                    sanity,
                    dropRate: dropRate.toFixed(2),
                    efficiency: isFinite(efficiencyValue) ? efficiencyValue.toFixed(2) : '∞',
                };
            }).sort((a, b) => a.efficiency - b.efficiency);
        }
    },
    watch: {
        selectedItemId(newId) { // Fetch drop data when a material is input
            if (newId) {
                this.fetchDropRates(newId);
            } else {
                this.dropData = [];
            }
        }
    },
    methods: {
        async fetchDropRates(itemId) {
            this.loading = true;    // Make it so that the data table displays that it is waiting for the server
            this.dataSource = DATA_SOURCE_UNKNOWN;

            const success = await this.fetchDropsFromAPI(itemId);
            if (!success) await this.fetchDropsFromBackup(itemId);

            this.loading = false;
        },
        async fetchDropsFromAPI(itemId) {
            try {
                const result = await axios.get('https://penguin-stats.io/PenguinStats/api/v2/result/matrix', {
                    params: { itemFilter: itemId, },
                    timeout: API_TIMEOUT
                });

                this.dropData = result.data.matrix || [];
                this.dataSource = DATA_SOURCE_API;
                return true;
            } catch (err) {
                console.warn('Live API failed:', err);
                return false;
            }
        },
        async fetchDropsFromBackup(itemId) {
            try {
                const result = await axios.get(new URL(`/src/data/matrix.json`, import.meta.url).href, {
                    timeout: FALLBACK_TIMEOUT
                });
                const fullMatrix = result.data.matrix || [];

                this.dropData = fullMatrix.filter(d => d.itemId === itemId);
                this.dataSource = DATA_SOURCE_FALLBACK;
            } catch (err) {
                console.error("Fallback matrix failed:", err);
                this.dropData = [];
            }
        },
        resolveStageCode(stageId) {
            const code = this.stageIdToCode[stageId] || stageId;
            if (stageId.startsWith("tough")) {
                return `${code} (tough)`
            }
            return code;
        }
    }
};
</script>
