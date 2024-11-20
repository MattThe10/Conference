<template>
    <div class="wrapper">
        <div class="wrapper-left">
            <h1>Login</h1>
            <p v-if="wrongCredentials" class="warning warning-par">Ľutujeme, zadali ste nesprávny email alebo heslo</p>
            <form action="" @submit.prevent="handleLogin" id="basic-form">
            <div class="wrapper-inp">
                <label for="email-inp">Email</label>
                <input type="email" id="email-inp" class="inp" v-model="email" required >
            </div>
            <div class="wrapper-inp">
                <label for="pass-inp">Heslo</label>
                <input type="password" id="pass-inp" class="inp" v-model="password" required>
            </div>
            <button type="submit" class="green-btn">Prihlásiť sa</button>
            </form>
            <p>Nemáte ešte účet? 
                <router-link to="/register">Zaregistrujte sa!</router-link>
            </p>
        </div>
        <div class="wrapper-right">
            <img src="@/assets/office-2.jpg" class="image" alt="Conference desk with people">
        </div>
    </div>
</template>

<script>
    import '@/styles/styles.css'
    // import { useLoginStore } from './stores/formData'
    // import { reactive } from 'vue'

    import axios from 'axios'

    const backend_url = process.env.VUE_APP_BACKEND_URL;

    export default {
        data() {
            return {
                email: '',
                password: '',
                dummy_email: 'admin@admin.com',
                dummy_password: 'admin',
                wrongCredentials: false
            }
        },
        methods: {
            handleLogin() {
                console.log('Trying to login as: ' , this.email)

                axios.post(`${backend_url}/login`, {
                    email: this.email,
                    password: this.password,
                })
                .then(response => {
                    console.log('Login successful: ', response.data);
                    this.$router.push('/home');
                })
                .catch(error => {
                    console.log('Login failed: ', error.response || error.message);
                });

                // if(this.email === this.dummy_email && this.password === this.dummy_password) {
                //     //Redirect to homepage after login
                //     console.clear()
                //     console.log('Succesfully logged in as ', this.email)
                //     this.$router.push('/home')
                // } else {
                //     console.log('Try again!')
                //     this.handleWrongCredentials()
                // }
                
                
            },

            handleWrongCredentials() {
                this.wrongCredentials = !this.wrongCredentials
            }
    },
//     setup() {
//     const loginStore = useLoginStore()
//     const form = reactive({
//       email: ''
//     })

//     const submitForm = () => {
//       loginStore.updateLoginData({ ...form })
//       // You can then clear the form, if desired
//     }

//     return { form, submitForm }
//   }
}
</script>

<style>

    .wrapper {
    margin-top: 5rem;
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
    align-items: center;
    height: 30rem;
}

.wrapper-left, .wrapper-right {
    height: 100%;
    background-color: #fff;
    display: flex;
    flex-direction: column;
}

.wrapper-left {
    width: 30%;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    justify-content: center;
    align-items: center;
}   

.wrapper-right {
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

.wrapper-inp{
    text-align: start;
    display: flex;
    flex-direction: column;
}

.image {
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    height: 100%;
}

.inp {
    width: 15rem;
    height: 1.8rem;
}

.green-btn {
    width: 100%;
    background-color: #52b69a;
    color: white;
    font-size: 1.5rem;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
    margin-bottom: 5px;
    padding: 3px;
    cursor: pointer;
}

p {
    font-size: 0.9rem;
}
</style>