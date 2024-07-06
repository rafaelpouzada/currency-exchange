<template>
    <b-container fluid>
        <b-row>
            <b-col cols="12" class="text-right mb-2">
                <b-button variant="success" @click="createQuotation">
                    <b-icon icon="plus-circle-fill"></b-icon> Criar Nova Cotação
                </b-button>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="12">
                <b-table striped hover :items="formattedCurrencies" :fields="fields"></b-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="12" class="pagination-controls">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalPages"
                    :per-page="pageSize"
                    @change="pageChanged"
                    aria-controls="my-table"
                ></b-pagination>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import axios from 'axios';
import moment from 'moment'; // Certifique-se de instalar o moment com `npm install moment` ou `yarn add moment`

export default {
    name: 'QuotationList',
    data() {
        return {
            currentPage: 1,
            pageSize: 5,
            totalPages: 0,
            currencies: [], // Keep this if you still need to use the raw API response elsewhere
            formattedCurrencies: [], // Added this line
            paginationLinks: {},
            fields: [
                { key: 'user_name', label: 'Usuário' },
                { key: 'from_currency', label: 'Moeda Origem' },
                { key: 'from_amount', label: 'Valor Origem' },
                { key: 'to_currency', label: 'Moeda Destino' },
                { key: 'amount', label: 'Valor Destino' },
                { key: 'created_at', label: 'Data da Cotação' },
                { key: 'conversion_rate', label: 'Taxa de Conversão' },
            ]
        };
    },
    created() {
        this.fetchCurrencies();
    },
    methods: {
        fetchCurrencies(page = this.currentPage) {
            const token = localStorage.getItem('user-token');
            axios.get(`/api/v1/currency-conversion?page=${page}&size=${this.pageSize}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    const { paging, data } = response.data;
                    this.currentPage = paging.number;
                    this.pageSize = paging.size;
                    this.totalPages = paging.total;
                    this.paginationLinks = paging.links;
                    this.formattedCurrencies = this.formatCurrenciesData(data); // Update this line
                })
                .catch(error => {
                    console.error("Erro ao buscar cotações:", error);
                });
        },
        formatCurrenciesData(data) {
            return data.map(item => ({
                ...item,
                created_at: moment(item.created_at).format('DD/MM/YYYY HH:mm:ss'),
                user_name: item.user ? item.user.name : 'Nome não disponível'
            }));
        },
        pageChanged(page) {
            this.currentPage = page;
            this.fetchCurrencies();
        },
        createQuotation() {
            this.$router.push({ name: 'admin.quotation' }); // Assumindo que você tem uma rota nomeada 'CreateQuotation'
        },
    },
}
</script>

<style>
.currency-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}
.pagination-controls {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.btn-success:hover {
    background-color: #28a745;
    border-color: #1e7e34;
    opacity: 0.85;
}
</style>
