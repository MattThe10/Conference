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
            <div>
                {{ role.name }}
            </div>
            <div>
                {{ user.name }}
                {{ user.surname }}
            </div>
            <div>
                {{ user.email }}
            </div>
            <div>
                {{ university.name }}
            </div>
            <div>
                {{ faculty.name }}
            </div>
        </div>
        <div>
            <button @click="logout()">
                Logout
            </button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                user: [],
                role: [], //Superadmin, admin, reviewer, student
                faculty: [],
                university: [],
            }
        },
        methods: {
            async getUser() {
                try {
                    console.log('Loading user data...');

                    const user_response = await axios.get('/api/current_user');
                    this.user = user_response.data;

                    const role_response = await axios.get(`/api/role/${this.user.roles_id}`);
                    this.role = role_response.data;

                    const faculty_response = await axios.get(`/api/faculty/${this.user.faculties_id}`);
                    this.faculty = faculty_response.data;

                    const university_response = await axios.get(`/api/university/${this.faculty.universities_id}`);
                    this.university = university_response.data;
                } catch (error) {
                    console.log('Error loading user data: ', error);
                }
            },
            async logout() {
                try {
                    console.log('Trying to logout...');

                    await axios.post('/logout');

                    console.log('Successfull logout');

                    this.$router.push('/login')
                } catch (error) {
                    console.log('Error while logout: ', error);
                }
            }
        },
        mounted() {
            this.getUser();
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
