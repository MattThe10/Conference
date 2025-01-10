<template>
    <NavBar></NavBar>
    <div class="main-wrapper">
        <form action="" id="profile-form" @submit.prevent="handleSubmit">
            <div class="profile-card">
                <h2>Upraviť profil</h2>
                <div class="card-wrapper">
                    <label for="name">Meno</label>
                    <input type="text" id="name" v-model="name">
                    <label for="surname">Priezvisko</label>
                    <input type="text" id="surname" v-model="surname">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="email">
                    <label for="university-faculty-inp">Univerzita a Fakulta</label>
                    <select id="university-faculty-inp" v-model="faculty_id" required>
                        <optgroup v-for="university in universities" :key="university.id" :label="university.name">
                            <option v-for="faculty in university.faculties" :key="faculty.id" :value="faculty.id">
                                {{ faculty.name }}
                            </option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="password-card">
                <h2>Zmeniť heslo</h2>
                <div class="card-wrapper">
                    <label for="current-password">Staré heslo</label>
                    <input type="password" id="current-password" v-model="current_password">
                    <label for="new-password">Nové heslo</label>
                    <input type="password" id="new-password" v-model="new_password">
                    <label for="confirm-password">Potvrdiť heslo</label>
                    <input type="password" id="confirm-password" v-model="new_password_confirmation">
                </div>
            </div>
        </form>
        <p v-for="(errorMessage, index) in errorMessages" :key="index" class="warning warning-par">{{ errorMessage }}
        </p>
        <div class="btn-div">
            <button type="submit" class="btn" id="btn-submit" form="profile-form">Uložiť</button>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import NavBar from './NavBar.vue'

export default {
    components: {
        NavBar
    },
    data() {
        return {
            name: '',
            surname: '',
            email: '',
            current_password: '',
            new_password: '',
            new_password_confirmation: '',
            faculty_id: '',
            universities: [],
            errorMessages: [],
        }
    },
    methods: {
        async getUser() {
            try {
                console.log("Loading user data...");

                const user_response = await axios.get("/api/current_user");
                const user = user_response.data;

                this.name = user.name;
                this.surname = user.surname;
                this.email = user.email;
                this.faculty_id = user.faculties_id;
            } catch (error) {
                console.log("Error loading user data: ", error);
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
        async handleSubmit() {
            try {
                await axios.post('/api/update_current_user', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    faculty_id: this.faculty_id,
                    current_password: this.current_password,
                    new_password: this.new_password,
                    new_password_confirmation: this.new_password_confirmation,
                });

                console.log('Successfull update');

                this.$router.push('/home')
            } catch (error) {
                console.log('Update error: ', error);

                if (error.response) {
                    if (error.response.data.errors) {
                        this.errorMessages = Object.values(error.response.data.errors).flat();
                    } else {
                        this.errorMessages = [error.response.data.message] || ['Uknown error'];
                    }
                }
            }
        },
    },
    mounted() {
        this.getUser();
        this.getUniversitiesAndFaculties();
    },
}
</script>

<style scoped>
.main-wrapper {
    width: 50rem;
    height: 30rem;
    background-color: #fff;
    border-radius: 20px;
    margin: 2rem auto;
}

.profile-card,
.password-card,
.card-wrapper {
    display: flex;
    flex-direction: column;
}

.profile-card,
.password-card {
    margin-top: 2.5rem;
    width: 20rem;
    height: 20rem;
    border: 1px solid #bfc0c0;
    border-radius: 15px;
    text-align: left;
    align-items: center;
    font-size: 1.5rem;
}

.profile-card h2,
.password-card h2 {
    text-align: center;
    margin-bottom: 1rem;
}

.profile-card input,
.profile-card select,
.password-card input {
    width: 15rem;
    height: 1.5rem;
}

.card-wrapper {
    gap: 0.2rem;
}

#profile-form {
    display: flex;
    flex-direction: row;
    justify-content: center;
    gap: 5rem;
}

.btn {
    padding: 10px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
    margin-top: 1rem;
    border-radius: 10px;
    position: relative;
    top: 1rem;
    left: 19rem;
}
</style>