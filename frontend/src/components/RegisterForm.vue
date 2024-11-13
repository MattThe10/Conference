<template>
    <h1>Login</h1>
    <div>
        <form action="" @submit.prevent="handleRegister">
            <label for="name-inp">Meno</label>
            <input type="text" id="name-inp" v-model="name">

            <label for="surname-inp">Priezvisko</label>
            <input type="text" id="surname-inp" v-model="surname">

            <label for="email-inp">Email</label>
            <input type="email" id="email-inp" v-model="email">

            <label for="pass-inp">Heslo</label>
            <input type="password" id="pass-inp" v-model="password">

            <button type="submit">Prihlásiť sa</button>
        </form>
        <p>Máte už účet? 
            <router-link to="/login">Prihláste sa!</router-link>
        </p>
    </div>
</template>
<script>

    export default {
        data() {
            return {
                name: '',
                surname: '',
                email: '',
                password: ''
            }
        },
        methods: {
            handleRegister() {
                console.log('Trying to register as ', this.email)
                this.$router.push('/login')
            }
        }
    }
</script>

<style>
</style>