<template>
    <h3 class="text-h4 text-lg-h3 text-center mb-8">{{ welcomeMessage }}</h3>
    <v-text-field v-model="firstName" label="First Name" class="mb-4"/>
    <v-text-field v-model="lastName" label="Last Name" class="mb-4"/>
    <p class="text-h5 text-center mb-4">
        Welcome to the Arknights Toolkit community! We're a group of dedicated Doctors who
        believe in sharing tools, strategies, and sanity-saving tips to help others succeed
        in Rhodes Island. Whether you're a casual or a hardcore strategist, this toolkit is
        for you.
    </p>
</template>

<script>
export default {
    name: 'AboutText',
    data() {
        return {
            firstName: "",
            lastName: "",
        }
    },
    computed: {
        welcomeMessage() {
            if (!this.firstName && !this.lastName) return "Welcome!";
            else if (this.firstName && !this.lastName) return `Welcome ${this.firstName.trim()}!`;
            return `Welcome ${this.firstName.trim()} ${this.lastName.trim()}!`;
        }
    }
}
</script>