<template>
    <div class="container mx-auto">
            <div class="text-center"><h3>Refund: {{ transaction.merchant_transaction_id}}</h3></div>
            <div class="max-w-md mx-auto my-10 bg-white p-5 rounded-md shadow-sm">
                <h5><strong>Amount:</strong></h5>
                <p>{{ formatCurrency(transaction.currency) }}{{ transaction.amount }}</p>

                <h5><strong>Merchant Transaction Id:</strong></h5>
                <p>{{ transaction.merchant_transaction_id }}</p>

                <h5><strong>Timestamp:</strong></h5>
                <p>{{ transaction.created_at }}</p>
                <hr>
                <div class="row container">
                    <router-link to="/transactions" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-green-400 hover:bg-green-600 hover:shadow-lg"><i class="fas fa-arrow-left"></i> Back</router-link>
                    <form><button type="submit" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-red-400 hover:bg-red-600 hover:shadow-lgy" @click="handleSubmit">Refund</button></form>
                </div>
            </div>
        </div>
</template>

<script>
export default {
    data() {
        return {
            transaction: {}
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            const get_url = '/api/transactions/show/' + this.$route.params.id
            this.$axios.get(get_url)
                .then(response => {
                    this.transaction = response.data;
                    this.formatData(this.transaction)
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    },
    methods: {
        handleSubmit() {
            var action_url = 'api/purchase/refund'
            console.log(action_url, this.transaction.id, this.transaction.transaction_id);    
            this.$axios.post('/sanctum/csrf-cookie').then(response => {
                    this.$axios.get(action_url, {
                        id: this.transaction.id
                    })
                        .then(response => {
                            if (response.data.success) {
                                console.log(response);
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
        },
        formatData(transaction) {
            transaction.created_at = this.formatDatetime(transaction.created_at)
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
                case "GBP":
                    return "£"
                default:
                    return ""
            }
        }
    }
}
</script>