<template>
    <div id="app">
        <b-navbar toggleable="lg" type="dark" variant="primary">
            <b-navbar-brand href="#">Currency Converter</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto">
                    <b-dropdown v-if="userData" right>
                        <template #button-content>
                            {{ userData.name }}
                        </template>
                        <b-dropdown-item @click="logout">Logout</b-dropdown-item>
                    </b-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <!-- Substituição para renderizar o componente baseado na rota -->
        <router-view />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'AdminComponent',
    computed: {
        ...mapGetters({
            userData: 'user/userData'
        })
    },
    methods: {
        logout() {
            localStorage.removeItem('user-token');
            localStorage.removeItem('userData');
            this.$router.push({name: 'login'});
        }
    }
}
</script>
