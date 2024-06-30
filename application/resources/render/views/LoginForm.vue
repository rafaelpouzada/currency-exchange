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
                        <v-btn text color="primary" @click="goToForgotPassword">Forgot Password?</v-btn>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
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
    }),

    methods: {
        validate() {
            if (this.$refs.form.validate()) {
                axios.post('/api/v1/auth/login', {
                    email: this.email,
                    password: this.password,
                })
                .then(response => {
                    // Handle successful registration
                    const accessToken = response.data.data.token.access_token;
                    localStorage.setItem('user-token', accessToken);
                    this.$store.commit('SET_USER_DATA', response.data.data);
                    this.$router.push({ name: 'admin.home' });
                })
                .catch(error => {
                    // Handle error during registration
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
        },
        goToForgotPassword() {
            this.$router.push({name: 'recovery-password'});
        }
    },
};
</script>

<style scoped lang="scss">
</style>
