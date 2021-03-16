<template>
    <div class="container mx-auto">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-danger" role="alert" v-if="error !== null">
                    {{ error }}
                </div>

                <div v-if="showPayment" class="card card-default">
                    <div class="text-center">
                <h1 class="my-3 text-3xl font-semibold text-black-700 dark:text-black-200">Payment Testing</h1>
                </div>
                    <div class="m-7">
                        <form>
                            <div class="mb-6">
                                <label for="amount" class="block mb-2 text-sm  text-black-600 dark:text-black-400">Amount</label>
                                <div class="col-md-6">
                                    <input id="amount" type="number" class="form-control rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none" v-model="amount" required
                                           autofocus autocomplete="off" min="1">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="reference" class="block mb-2 text-sm  text-black-600 dark:text-black-400">Reference:</label>
                                <div class="col-md-6">
                                    <input id="reference" type="text" class="form-control rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none" v-model="reference"
                                           required autocomplete="off">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="currency" class="block mb-2 text-sm  text-black-600 dark:text-black-400">Currency</label>
                                <div class="col-md-6">
                                    <select id="currency" class="form-control rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none" v-model="selected" >
                                        <option v-for="currency in currencies" :value="currency.value" v-bind:key="currency.value">
                                            {{ currency.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-10">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="focus:outline-none  text-gray-200  px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-700 hover:shadow-lgy" @click="handleSubmit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div v-else class="card card-default">
                    <div class="card-header text-center"><h3>Billing</h3></div>
                    <div class="card-body">

                        <form method="post" v-bind:action="action_url" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

  export default {
    data() {
      return {
        amount: 0,
        reference: "",
        selected: 'GBP',
        currencies: [
          { value: 'GBP', text: 'GBP' },
          { value: 'EUR', text: 'EUR' },
          { value: 'USD', text: 'USD'},
        ],
        error: null,
        showPayment: true,
        payment_id: "",
        action_url: "/confirmation/",
      }
    },
    methods: {
    handleSubmit(e) {
        e.preventDefault()
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/purchase/details', {
                amount: this.amount,
                reference: this.reference,
                currency: this.selected

            })
                .then(response => {
                    if (response.data.success) {
                        const result = JSON.parse(response.data.message)
                        this.createBillingScript(result.id)
                        this.showPayment = false
                        this.payment_id = result.id
                        this.action_url = "/api/purchase/confirm/" + result.id + "/" + window.Laravel.user.id
                        console.log(this.action_url);
                    } else {
                        this.error = response.data.message
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
            })
        },
        createBillingScript(payment_id) {
            let externalScript = document.createElement('script')
            const source_url = 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=' + payment_id
            console.log(source_url);
            externalScript.setAttribute('src', source_url)
            document.head.appendChild(externalScript)
        }
  }

}
</script>