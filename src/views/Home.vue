<template>
    <HeroBanner />
    <WelcomeSection />
    <TrustReasons />
</template>

<script>
import HeroBanner from '../components/home/HeroBanner.vue'
import WelcomeSection from '../components/home/WelcomeSection.vue'
import TrustReasons from '../components/home/TrustReasons.vue';

export default {
    name: 'Home',
    components: {
        HeroBanner,
        WelcomeSection,
        TrustReasons
    }
}
</script>