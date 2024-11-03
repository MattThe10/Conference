<template>
    <div id="wrapper">
        <div id="wrapper-left">
            <h1>Login</h1>
            <form action="" @submit.prevent="handleLogin" id="login-form">
            <div class="wrapper-inp">
                <label for="email-inp">Email</label>
                <input type="email" id="email-inp" v-model="email" required >
            </div>
            <div class="wrapper-inp">
                <label for="pass-inp">Heslo</label>
                <input type="password" id="pass-inp" v-model="password" >
            </div>
            <button type="submit" id="login-btn">Prihlásiť sa</button>
            </form>
            <p>Nemáte ešte účet? 
                <router-link to="/register">Zaregistrujte sa!</router-link>
            </p>
        </div>
        <div id="wrapper-right">
            <img src="@/assets/office-2.jpg" alt="Conference desk with people">
        </div>
    </div>
</template>
<script>
    import '@/styles/styles.css'
    export default {
        data() {
            return {
                email: '',
                password: '',
                dummy_email: 'admin@admin.com',
                dummy_password: 'admin'
            }
        },
        methods: {
            handleLogin() {
                console.log('Trying to login as: ' , this.email)
                if(this.email === this.dummy_email && this.password === this.dummy_password) {
                    //Redirect to homepage after login
                    console.clear()
                    console.log('Succesfully logged in as ', this.email)
                    this.$router.push('/home')
                } else {
                    console.log('Try again!')
                }
                
                
            }
    }
    }
</script>

<style>

#wrapper {
    margin-top: 5rem;
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
    align-items: center;
    height: 30rem;
}

#wrapper-left, #wrapper-right {
    height: 100%;
    background-color: #fff;
}

#wrapper-left {
    width: 30%;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    justify-content: center;
}   

#wrapper-right {
    width: 30%;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
}

h1 {
    text-transform: uppercase;
    font-size: 2.5rem;
}

#login-form {
    margin-top: 25px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    padding: 5px;
    border-top-left-radius: 0;
    border-top-right-radius: 10px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

div {
    display: flex;
    flex-direction: column;
}

.wrapper-inp{
    text-align: start
}

img {
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    height: 100%;
}

input {
    width: 15rem;
    height: 1.8rem;
}

#login-btn {
    width: 45%;
    background-color: #52b69a;
    color: white;
    font-size: 1.5rem;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
    padding: 3px;
    cursor: pointer;
}

p {
    font-size: 0.9rem;
}
</style>