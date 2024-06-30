<template>
    <div id="app">
        <b-navbar toggleable="lg" type="dark" variant="primary">
            <b-navbar-brand href="#">Currency Converter</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item href="/login">Sign In</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>

        <v-container fluid fill-height>
            <v-layout row wrap align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <v-card class="elevation-12">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>Currency Converter</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <CurrencyConverter />
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import CurrencyConverter from './components/CurrencyConverter.vue'
export default {
    name: 'LandingPage',
    components: {
        CurrencyConverter
    }
}
</script>

<style scoped>
.elevation-12 {
    box-shadow: none !important;
}
</style>
