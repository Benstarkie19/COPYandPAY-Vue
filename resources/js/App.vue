<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white mx-auto ">
            <div class="collapse navbar-collapse">
  <header class="text-gray-100 bg-gray-900 body-font shadow w-full">
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
            <nav class="flex lg:w-2/5 flex-wrap items-center text-base md:ml-auto">
             <div class="navbar-nav " v-if="isLoggedIn">
                    <router-link @click.native="showSummary=false" to="/purchase" class="nav-item nav-link">Payment Testing</router-link>
                    &nbsp;
                    &nbsp;
                    <router-link @click.native="showSummary=false" to="/transactions" class="nav-item nav-link"> Old Transactions</router-link>
                    &nbsp;&nbsp;
                    <a class="nav-item nav-link" style="cursor: pointer;" @click="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    &nbsp;&nbsp;
                </div>
            <div v class="navbar-nav " v-else>
                    <router-link to="/login" class="nav-item nav-link "> Login</router-link>
                    &nbsp;
                    &nbsp;
                    <router-link to="/register" class="nav-item nav-link"> Register</router-link>
                </div>
            </nav>
            <a class="flex order-first lg:order-none lg:w-1/5 title-font font-medium items-center lg:items-center lg:justify-center mb-4 md:mb-0">
                <span class="ml-3 text-xl">Total Processing</span>
            </a>
            <div class="lg:w-2/5 inline-flex lg:justify-end ml-5 lg:ml-0 ">
                <a href="https://github.com/Benstarkie19" class="bg-indigo-700 hover:bg-indigo-500 text-white ml-4 py-2 px-3 rounded-lg">
                    Github
                </a>
            </div>
        </div>
    </header>
            </div>
        </nav>
        <br/>
        <router-view/>

    
    </div>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
            showSummary: false,
            user_id_link: "",
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
            this.user_id_link = "/transactions/getAll/" + window.Laravel.user.id
            console.log(window.Laravel.user.id);
        }
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>