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
                    <label for="faculty-inp">Univerzita a fakulta</label>
                    <select id="faculty-inp" v-model="faculty_id" required>
                        <option disabled value="">
                            Vyber univerzitu a fakultu
                        </option>
                        <optgroup v-for="university in universities" :key="university.id" :label="university.name">
                            <option v-for="faculty in university.faculties" :key="faculty.id" :value="faculty.id">
                                {{ faculty.name }}
                            </option>
                        </optgroup>
                    </select>
                </div>
                <div class="wrapper-inp">
                    <label for="pass-inp">Heslo*</label>
                    <input type="password" id="pass-inp" v-model="password" required>
                </div>
                <div class="wrapper-inp">
                    <label for="pass-confirmation-inp">Heslo (znovu)*</label>
                    <input type="password" id="pass-confirmation-inp" v-model="password_confirmation" required>
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

    import axios from 'axios'

    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;

    const backend_url = process.env.VUE_APP_BACKEND_URL;

    export default {
        data() {
            return {
                universities: [],
                faculties: [],
                name: '',
                surname: '',
                email: '',
                password: '',
                password_confirmation: '',
                faculty_id: '',
                errorMessage: ''
            }
        },
        mounted() {
            this.fetchUniversities();
            this.fetchFaculties();
        },
        methods: {
            fetchUniversities() {
                axios.get(`${backend_url}/api/universities`)
                    .then(response => {
                        this.universities = response.data.map(university => ({
                            ...university,
                            faculties: []
                        }));
                    })
                    .catch(error => console.log(error));
            },
            fetchFaculties() {
                axios.get(`${backend_url}/api/faculties`)
                    .then(response => {
                        this.faculties = response.data;

                        this.faculties.forEach(faculty => {
                            const university = this.universities.find(u => u.id == faculty.universities_id);
                            if (university) {
                                university.faculties.push(faculty);
                            }
                        });
                    })
                    .catch(error => console.log(error));
            },
            handleRegister() {
                console.log('Trying to register as ', this.email)

                axios.get(`${backend_url}/sanctum/csrf-cookie`)
                    .then(() => {
                        axios.post(`${backend_url}/register`, {
                            name: this.name,
                            surname: this.surname,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            faculty_id: this.faculty_id
                        })
                        .then(response => {
                            console.log('Registration successful: ', response.data);
                            this.$router.push('/home');
                        })
                        .catch(error => {
                            console.log('Registration failed: ', error.response || error.message);
                        });
                    });

                //If true, we land on login page 
                // if(this.handlePasswordValidation()) {
                //      this.$router.push('/login')
                // } else {
                //      console.log('Wrong val')
                // }
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