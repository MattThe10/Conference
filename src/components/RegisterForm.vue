<template>
    <p v-if="errorMessage" class="warning warning-par">{{ errorMessage }}</p>
    <div class="wrapper">
        <div class="wrapper-left">
            <form action="" @submit.prevent="handleRegister" id="basic-form">
                <div class="wrapper-inp">
                    <label for="name-inp">Meno</label>
                    <input type="text" id="name-inp" v-model="name" required>
                </div>
                <div class="wrapper-inp">
                    <label for="surname-inp">Priezvisko</label>
                    <input type="text" id="surname-inp" v-model="surname" required>
                </div>
                <div class="wrapper-inp">
                    <label for="email-inp">Email</label>
                    <input type="email" id="email-inp" v-model="email" required>
                </div>
                <div class="wrapper-inp">
                    <label for="school-inp">Škola</label>
                    <input type="text" id="school-inp" v-model="school" required>
                </div>
                <div class="wrapper-inp">
                    <label for="faculty-inp">Fakulta</label>
                    <input type="text" id="faculty-inp" v-model="faculty" required>
                </div>
                <div class="wrapper-inp">
                    <label for="pass-inp">Heslo*</label>
                    <input type="password" id="pass-inp" v-model="password" required>
                </div>
                <button type="submit" class="green-btn">Registrovať sa</button>
            </form>
        <p>Máte už účet? 
            <router-link to="/login">Prihláste sa!</router-link>
        </p>
        </div>
        <div class="wrapper-right">
            <img src="@/assets/office-2.jpg" alt="" class="image">
        </div>
    </div>
    <p>*Heslo musí obsahovať minimálne 8 znakov,
         pričom minimálne 1 z nich musí byť veľké písmeno a číslo
    </p>
    
</template>
<script>

    export default {
        data() {
            return {
                name: '',
                surname: '',
                email: '',
                password: '',
                school: '',
                faculty: '',
                errorMessage: ''
            }
        },
        methods: {
            handleRegister() {
                console.log('Trying to register as ', this.email)
                //If true, we land on login page 
                if(this.handlePasswordValidation()) {
                     this.$router.push('/login')
                } else {
                     console.log('Wrong val')
                }
                //this.$router.push('/login')

                
                
            },
            //TO FIX
            handlePasswordValidation() {
                const regex = /^(?=.*[A-Z])(?=.*\d).{8,}$/
                //Wrong password
                if(!regex.test(this.password)) {
                    this.errorMessage = 'Heslo musí byť 8 znakov dlhé a obsahovať aspoň jedno číslo a veľké písmeno'
                //Correct password
                } else {
                    this.errorMessage = ''
                    return true
                }
                
            }
        }
    }
</script>

<style>

    .green-btn {
        padding: 5px;
    }
</style>