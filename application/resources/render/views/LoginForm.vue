<template>
    <v-container fluid fill-height>
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar color="primary" dark flat>
                        <v-toolbar-title>Login</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form" v-model="valid" lazy-validation>
                            <v-text-field
                                v-model="email"
                                :rules="emailRules"
                                :error-messages="apiErrors.email"
                                @input="clearApiError('email')"
                                label="E-mail"
                                required
                            ></v-text-field>

                            <v-text-field
                                v-model="password"
                                :rules="passwordRules"
                                :error-messages="apiErrors.password"
                                @input="clearApiError('password')"
                                label="Password"
                                type="password"
                                required
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" :disabled="!valid" @click="validate">Login</v-btn>
                    </v-card-actions>
                    <v-card-text class="text-center">
                        <v-btn text color="primary" @click="goToRegister">Register</v-btn>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import axios from "axios";

import { mapGetters } from 'vuex';

export default {
    computed: {
        ...mapGetters('user', ['userData']), // Use 'user/userData' se namespaced
    },
    created() {
        console.log(this.userData);  // Verificar o valor do getter
    },
    data: () => ({
        valid: true,
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
        password: '',
        passwordRules: [
            v => !!v || 'Password is required',
        ],
        apiErrors: {},
    }),

    methods: {
        validate() {
            if (this.$refs.form.validate()) {
                axios.post('/api/v1/auth/login', {
                    email: this.email,
                    password: this.password,
                })
                    .then(response => {
                        const userData      = response.data.data;
                        const accessToken   = userData.token.access_token;
                        localStorage.setItem('user-token', accessToken);
                        localStorage.setItem('userData', JSON.stringify(userData));
                        this.$store.dispatch('user/setUserData', userData); // Adicione o namespace 'user'
                        this.$router.push({ name: 'admin.home' });
                    })
                    .catch(error => {
                        let errorMessage = 'An error occurred while authenticating';
                        if (error.response && error.response.data && error.response.data.errors) {
                            errorMessage   = error.response.data.message;
                            this.apiErrors = error.response.data.errors;
                        }
                        this.$toast.error(errorMessage);
                    });
            }
        },
        clearApiError(field) {
            if (this.apiErrors[field]) {
                this.apiErrors[field] = null;
                this.valid = this.$refs.form.validate();
            }
        },
        goToRegister() {
            this.$router.push({name: 'register'});
        }
    },
};
</script>

<style scoped lang="scss">
</style>
