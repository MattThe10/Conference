<template>
    <NavBar></NavBar>
    <div class="main-wrapper">
        <form action="" id="profile-form">
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
                    <input type="password" id="current-password" v-model="password">
                    <label for="new-password">Nové heslo</label>
                    <input type="password" id="new-password" v-model="new_password">
                    <label for="confirm-password">Potvrdiť heslo</label>
                    <input type="password" id="confirm-password" v-model="password_confirmation">
                </div>
            </div>
        </form>
        <div>
            <button type="submit" id="btn-submit" form="profile-form">Uložiť</button>
        </div>
    </div>
</template>
<script>
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
            password: '',
            new_password: '',
            password_confirmation: '',
            faculty_id: '',
            universities: [],

        }
    }
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

#btn-submit {
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