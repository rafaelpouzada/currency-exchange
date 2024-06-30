<template>
    <div class="currency-converter">
        <b-container>
            <b-row>
                <b-col class="text-center">
                    <h1>Currency Converter</h1>
                    <p>Check foreign currency exchange rates</p>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <b-table striped hover :items="currencies" :fields="fields" :tbody-tr-class="rowClass">
                        <template #cell(currency)="data">
                            <span>{{ data.item.currency }}</span>
                        </template>
                        <template #cell(amount)="data">
                            <span>{{ data.item.amount }}</span>
                        </template>
                    </b-table>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: [
                { key: 'currency', label: 'Currency' },
                { key: 'amount', label: 'Amount' },
            ],
            currencies: [
                { currency: 'Euro', amount: '1', isReference: true },
                { currency: 'Real', amount: '6.5' },
                { currency: 'US Dollar', amount: '1.2' },
                { currency: 'Japanese Yen', amount: '130' },
            ],
        }
    },
    methods: {
        rowClass(item) {
            if (item.isReference) {
                return 'reference-currency'
            }
            return ''
        }
    }
}
</script>

<style>
.currency-converter {
    padding: 20px;
}

.text-center {
    text-align: center;
}

.reference-currency {
    background-color: #f0f8ff !important; /* Light blue background for highlighting */
    font-weight: bold;
}
</style>
