<template>
    <div class="container mx-auto">
        <div class="max-w-md mx-auto my-10 bg-white p-5 rounded-md shadow-sm">
            <div class="text-center">
                <h1 class="my-3 text-3xl font-semibold text-black-700 dark:text-black-200">Register</h1>
            </div>
            <div class="m-7">
                <form>
                
                <div class="alert alert-danger" role="alert" v-if="error !== null">
                    {{ error }}
                </div>

                    <div class="mb-6 md:my-6">
                        <label for="name" class="block mb-2 text-sm  text-black-600 dark:text-black-400">Name</label>
                        <div class="col-md-6">
                            <input id="name"  type="email" placeholder="Benjamin" class="form-control rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none"   v-model="name" required  autofocus autocomplete="off">
                    </div>
                    </div>
                    <div class="mb-6 md:my-6">
                        <label for="email"  class="block mb-2 text-sm text-black-600 dark:text-black-400">Email Address</label>
                        </div>
                         <div class="col-md-6">
                             <input id="email" type="email" placeholder="you@company.com" class="form-control  rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none" v-model="email" required autofocus autocomplete="off">
                                </div>
                    <div class="mb-6 md:my-6">
                        <label for="password" class="block mb-2 text-sm  text-black-600 dark:text-black-400">Password</label>
                        <div class="col-md-6">
                            <input id="password" type="password" placeholder="1234" class="form-control rounded px-4 w-full py-1 bg-gray-200  border border-gray-400 mb-6 text-gray-700 placeholder-gray-700 focus:bg-white focus:outline-none" v-model="password" required autocomplete="off">
                            </div>
                            </div>

                    <div class="items-stretch md:items-center">
                        <button type="submit" class="focus:outline-none   text-gray-200  px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-700 hover:shadow-lgy" @click="handleSubmit"> Register </button>
                        &nbsp;
                        <router-link to="/login" class="focus:outline-none   text-gray-200  px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-700 hover:shadow-lgy "> Login</router-link>
                        </div>
                </form>
            </div>
        </div>
    </div>


         
</template>

<script>
export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            error: null
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                window.location.href = "/login"
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('dashboard');
        }
        next();
    }
}
</script>