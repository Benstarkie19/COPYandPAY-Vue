<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header text-center"><h3>Transaction History</h3></div>
            <div class="card-body">
                <table class="table table-bordered   text-center">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Merchant Transaction Id</th>
                            <th>Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="transaction in transactions" :key="transaction.id">
                            <td>{{ formatCurrency(transaction.currency) }}{{ transaction.amount }}</td>
                            <td>{{ transaction.merchant_transaction_id }}</td>
                            <td>{{ formatDatetime(transaction.created_at) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <router-link :to="{name: 'showtransaction', params: { id: transaction.id }}" class="btn btn-primary">Show
                                    </router-link>
                                    <router-link :to="{name: 'refund', params: { id: transaction.id }}" class="btn btn-danger" v-if="transaction.refunded === 0" >Refund
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</template>

<script>
export default {
    data() {
        return {
            transactions: []
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            const transaction_url = '/api/transactions/' + window.Laravel.user.id
            console.log(transaction_url);
            this.$axios.get(transaction_url)
                .then(response => {
                    this.transactions = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        deleteBook(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/transactions/delete/${id}`)
                    .then(response => {
                        let i = this.books.map(item => item.id).indexOf(id); // find index of your object
                        this.books.splice(i, 1)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        formatDatetime(dt) {
            let formatDDt = Intl.DateTimeFormat('en-US',{
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: "numeric",
            minute: "2-digit",
            second: "2-digit"}).format(new Date(dt));
            return formatDDt
        },
        formatCurrency(currency) {
            switch (currency) {
                case "EUR":
                    return "€"
            
                case "USD":
                    return "$"

                default:
                    return "£"
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>