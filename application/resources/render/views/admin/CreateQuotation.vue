<template>
    <v-container fluid fill-height>
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar color="primary" dark flat>
                        <v-toolbar-title>Login</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form @submit.prevent="submitQuotation">
                            <v-select
                                v-model="quotationForm.fromCurrency"
                                :items="currencyOptions"
                                label="Moeda Origem"
                                required
                            ></v-select>
                            <v-text-field
                                v-model="quotationForm.fromAmount"
                                label="Valor Origem"
                                required
                            ></v-text-field>
                            <v-select
                                v-model="quotationForm.toCurrency"
                                :items="currencyOptions"
                                label="Moeda Destino"
                                required
                            ></v-select>
                            <v-btn type="submit" color="primary">Cotar</v-btn>
                        </v-form>
                    </v-card-text>
                    <v-card-text v-if="quotationResponse">
                        <b-col cols="12">
                            <b-card title="Resultado da Cotação">
                                <p><strong>Moeda Origem:</strong> {{ quotationResponse.from_currency }}</p>
                                <p><strong>Valor Origem:</strong> {{ quotationResponse.from_amount }}</p>
                                <p><strong>Moeda Destino:</strong> {{ quotationResponse.to_currency }}</p>
                                <p><strong>Valor Destino:</strong> {{ quotationResponse.amount }}</p>
                                <p><strong>Taxa de Conversão:</strong> {{ quotationResponse.conversion_rate }}</p>
                                <p><strong>Data da Cotação:</strong> {{ new Date(quotationResponse.created_at).toLocaleString() }}</p>
                                <b-button variant="primary" @click="backToList">Voltar para Listagem</b-button>
                            </b-card>
                        </b-col>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    name: 'CreateQuotation',
    data() {
        return {
            quotationForm: {
                fromCurrency: '',
                fromAmount: 0,
                toCurrency: ''
            },
            currencyOptions: [
                { value: null, text: 'Selecione uma moeda' },
                { value: 'BRL', text: 'Real Brasileiro (BRL)' },
                { value: 'USD', text: 'Dólar Americano (USD)' },
                { value: 'EUR', text: 'Euro (EUR)' },
                { value: 'JPY', text: 'Iene Japonês (JPY)' }
            ],
            quotationResponse: null,
        };
    },
    methods: {
        submitQuotation() {
            const token = localStorage.getItem('user-token');
            const requestData = {
                from_currency: this.quotationForm.fromCurrency,
                to_currency: this.quotationForm.toCurrency,
                amount: parseFloat(this.quotationForm.fromAmount) // Garante que o valor seja um float
            };
            axios.post('/api/v1/currency-conversion', requestData, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    this.quotationResponse = response.data.data;
                    this.$toast.success('Cotação realizada com sucesso.');
                })
                .catch(error => {
                    this.$toast.error('Erro ao realizar a conversão.');
                });
        },
        backToList() {
            this.$router.push({ name: 'admin.home' });
        },
    },
}
</script>

<style>
/* Add styles for your form if necessary */
</style>
