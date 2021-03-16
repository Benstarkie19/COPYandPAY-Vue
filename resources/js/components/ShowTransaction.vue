<template>
    <div class="container mx-auto">
            <div class="text-center"><h1>Old Transaction: {{ transaction.merchant_transaction_id}}</h1></div>
             <div class="max-w-md mx-auto my-10 bg-white p-5 rounded-md shadow-sm">
                <div v-for="(value, key) in transaction" :key="value.id">
                    <h5><strong>{{ formatHeader(key) }}:</strong></h5>
                    <p>{{ value }}</p>
                </div>
                <hr>
                <router-link to="/transactions" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-700 hover:shadow-lgy"> Back</router-link>
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
        formatData(transaction) {
            transaction.created_at = this.formatDatetime(transaction.created_at)
            if (transaction.refunded) { transaction.refunded = "True" } else { transaction.refunded = "False" }
            delete transaction['id']
            delete transaction['user_id']
            delete transaction['updated_at']
        },
        formatHeader(headerName) {
            var res = headerName.split('_');
            res.forEach((val, index) => res[index] = this.capitalizeLetter(val));
            return res.join(' ')
        },
        capitalizeLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
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
    }
}
</script>