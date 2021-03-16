<template>
	&nbsp;
    	&nbsp;
    <div class="container mx-auto">
                <table class="min-w-full table-auto">
                    <thead class="justify-between items-center">
                        <tr class="bg-indigo-700 text-white ml-4 py-2 px-3 rounded-lg items-center ">
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
                                	&nbsp;
                                <div class="btn" role="group">
                                    <router-link :to="{name: 'showtransaction', params: { id: transaction.id }}" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-green-400 hover:bg-green-600 hover:shadow-lgy">Show
                                    </router-link>
                                    	&nbsp;
                                    <router-link :to="{name: 'refund', params: { id: transaction.id }}" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-red-400 hover:bg-red-600 hover:shadow-lgy" v-if="transaction.refunded === 0" >Refund
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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