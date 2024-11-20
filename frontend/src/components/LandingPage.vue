<template>
    <!-- <div class="landing-wrapper">
        <div id="panel">
            <p class="router-p"><router-link to="/home">Home</router-link></p>
            <p class="router-p"><router-link to="/login">Login</router-link></p>
            <p class="router-p">{{ dummy_email }}</p>
        </div>
        <div id="content-page">
            <p>fewg</p>
        </div>
    </div> -->
    <div>
        <div>
            {{ role.name }}
        </div>
        <div>
            {{ user.name }} {{ user.surname }}
        </div>
        <div>
            {{ user.email }}
        </div>
    </div>
    <button @click="logout">
        Logout
    </button>
</template>

<script>

    import axios from 'axios'

    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;

    const backend_url = process.env.VUE_APP_BACKEND_URL;

    export default {
        data() {
            return {
                role: '', //Superadmin, admin, reviewer, student
                user: '',
            }
        },
        mounted() {
            this.getUser();
        },
        methods: {
            // Get current authenticated user informations
            getUser() {
                axios.get(`${backend_url}/api/current_user`)
                    .then(response => {
                        this.user = response.data;
                        if (this.user.roles_id) {
                            this.getRole();
                        }
                    })
                    .catch(error => console.log(error));
            },
            // Get Role of current authenticated user informations
            getRole() {
                axios.get(`${backend_url}/api/role/${this.user.roles_id}`)
                    .then(response => this.role = response.data)
                    .catch(error => console.log(error));
            },
            logout() {
                axios.post(`${backend_url}/logout`)
                    .then(() => {
                        console.log('Logged out');
                        this.$router.push('/login');
                    })
                    .catch(error => console.log(error));
            }
        }
    }  
</script>

<style>
    .landing-wrapper {
        display: flex;
        flex-direction: row;
        width: 100%;
        background-color: aqua;
    }

    #panel {
        background-color: black;
        height: 100%;
        width: 15%;
        position: fixed;
    }

    .router-p {
        width: 100%;
        height: 3rem;
        background-color: aliceblue;
        border: 1px solid grey;
        padding: 5px;
        font-size: 1.5rem;
        text-align: center; 
        cursor: pointer;
    }

    a:-webkit-any-link {
        text-decoration: none;
    }

    #content-page {
        background-color: blue;
        width: 100%;
        height: 100%;
    }
</style>
