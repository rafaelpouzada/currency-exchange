<template>
    <v-container fluid fill-height>
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar color="primary" dark flat>
                        <v-toolbar-title>Register</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form" v-model="valid" lazy-validation>
                            <v-text-field
                                v-model="name"
                                :rules="nameRules"
                                :error-messages="apiErrors.name"
                                @input="clearApiError('name')"
                                label="Name"
                                required
                            ></v-text-field>

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

                            <v-text-field
                                v-model="passwordConfirmation"
                                :rules="passwordConfirmationRules"
                                :error-messages="apiErrors.passwordConfirmation"
                                @input="clearApiError('passwordConfirmation')"
                                label="Confirm Password"
                                type="password"
                                required
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" :disabled="!valid" @click="register">Register</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import axios from 'axios';

export default {
    name: 'RegisterForm',
    data: () => ({
        valid: true,
        name: '',
        nameRules: [
            v => !!v || 'Name is required',
        ],
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
        password: '',
        passwordRules: [
            v => !!v || 'Password is required',
            v => v.length >= 8 || 'Password must be at least 8 characters',
        ],
        passwordConfirmation: '',
        apiErrors: {},
    }),
    computed: {
        passwordConfirmationRules() {
            return [
                v => !!v || 'Password confirmation is required',
                v => v === this.password || 'Passwords must match',
            ];
        },
    },

    methods: {
        register() {
            if (this.$refs.form.validate()) {
                axios.post('/api/v1/auth/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
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
                     let errorMessage = 'An error occurred while registering';
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
    },
};
</script>

<style scoped lang="scss">
</style>
