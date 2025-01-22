<template>
    <p v-for="(errorMessage, index) in errorMessages" :key="index" class="warning warning-par">{{ errorMessage }}</p>
    <div class="wrapper">
        <div class="wrapper-left">
            <form action="" @submit.prevent="handleRegister" id="basic-form">
                <div class="wrapper-inp">
                    <label for="name-inp">Meno</label>
                    <input type="text" id="name-inp" v-model="name" required class="inp">
                </div>
                <div class="wrapper-inp">
                    <label for="surname-inp">Priezvisko</label>
                    <input type="text" id="surname-inp" v-model="surname" required class="inp">
                </div>
                <div class="wrapper-inp">
                    <label for="email-inp">Email</label>
                    <input type="email" id="email-inp" v-model="email" required class="inp">
                </div>
                <div class="wrapper-inp">
                    <label for="university-faculty-inp">Univerzita a Fakulta</label>
                    <select id="university-faculty-inp" v-model="faculty_id" required class="inp">
                        <optgroup v-for="university in universities" :key="university.id" :label="university.name">
                            <option v-for="faculty in university.faculties" :key="faculty.id" :value="faculty.id">
                                {{ faculty.name }}
                            </option>
                        </optgroup>
                    </select>
                </div>
                <div class="wrapper-inp">
                    <label for="pass-inp">Heslo*</label>
                    <input type="password" id="pass-inp" v-model="password" required class="inp">
                </div>
                <div class="wrapper-inp">
                    <label for="pass-confirmation-inp">Heslo (znovu)*</label>
                    <input type="password" id="pass-confirmation-inp" v-model="password_confirmation" required
                        class="inp">
                </div>
                <button type="submit" class="green-btn btn">Registrovať sa</button>
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

import axios from 'axios';

export default {
    data() {
        return {
            name: '',
            surname: '',
            email: '',
            password: '',
            password_confirmation: '',
            faculty_id: '',
            errorMessages: [],
            universities: [],
        }
    },
    methods: {
        async handleRegister() {
            try {
                console.log('Trying to register...');

                await axios.post('/register', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    faculty_id: this.faculty_id
                });

                console.log('Successfull registration');

                this.$router.push('/home')
            } catch (error) {
                console.log('Error while registration: ', error);

                if (error.response) {
                    if (error.response.data.errors) {
                        this.errorMessages = Object.values(error.response.data.errors).flat();
                    } else {
                        this.errorMessages = error.response.data.message || 'Uknown error';
                    }
                }
            }
        },

        async getUniversitiesAndFaculties() {
            try {
                console.log('Loading universities and faculties...');

                const [universities_response, faculties_response] = await Promise.all([
                    axios.get('/api/universities'),
                    axios.get('/api/faculties'),
                ]);

                const universities = universities_response.data;
                const faculties = faculties_response.data;

                const universities_with_faculties = universities.map(university => {
                    return {
                        ...university,
                        faculties: faculties.filter(faculty => faculty.universities_id == university.id)
                    };
                });

                this.universities = universities_with_faculties;
            } catch (error) {
                console.log('Error loading universities: ', error);
            }
        },
    },
    mounted() {
        this.getUniversitiesAndFaculties();
    }
}
</script>

<style>
.green-btn {
    padding: 5px;
}
</style>