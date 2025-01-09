<template>
    <NavBar></NavBar>
    <div>
        <h2>Zoznam konferencií</h2>
        <div class="conference-wrapper">
            <div class="conference-display">
                <ConferenceItem v-for="conference in conferences" key="conference.id" :conference="conference"
                    @openForm="openForm"></ConferenceItem>
            </div>
            <div v-if="selectedConference" class="modal-backdrop conference-button" @click="closeForm">
                <div class="modal-content" @click.stop>
                    <button class="close-btn" @click="closeForm">✖</button>
                    <ArticlesForm :conference="selectedConference" @close="closeForm" />
                </div>
            </div>
        </div>
    </div>
    <div id="user-wrapper">
        <div id="user-details">
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
            <button @click="logout()" id="btn-logout">Logout</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import NavBar from "./NavBar.vue";
import ConferenceItem from '@/components/ConferenceItem.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'

export default {
    components: {
        NavBar,
        ConferenceItem,
        ArticlesForm
    },
    data() {
        return {
            user: [],
            role: [], //Superadmin, admin, reviewer, student
            faculty: [],
            university: [],
            //Sem sa pridavaju konferencie
            conferences: [
                { id: 1, name: 'Konferencia IT', date: '2025-03-10' },
                { id: 2, name: 'AI Konferenia', date: '2025-04-15' },
            ],
            selectedConference: null,
        };
    },
    methods: {
        async getUser() {
            try {
                console.log("Loading user data...");

                const user_response = await axios.get("/api/current_user");
                this.user = user_response.data;

                const role_response = await axios.get(
                    `/api/role/${this.user.roles_id}`
                );
                this.role = role_response.data;

                const faculty_response = await axios.get(
                    `/api/faculty/${this.user.faculties_id}`
                );
                this.faculty = faculty_response.data;

                const university_response = await axios.get(
                    `/api/university/${this.faculty.universities_id}`
                );
                this.university = university_response.data;
            } catch (error) {
                console.log("Error loading user data: ", error);
            }
        },
        async logout() {
            try {
                console.log("Trying to logout...");

                await axios.post("/logout");

                console.log("Successfull logout");

                this.$router.push("/login");
            } catch (error) {
                console.log("Error while logout: ", error);
            }
        },
        openForm(conference) {
            this.selectedConference = conference;
        },
        closeForm() {
            this.selectedConference = null;
        },
    },
    mounted() {
        this.getUser();
    },
};
</script>

<style scoped>
#user-wrapper {
    display: flex;
    flex-direction: column;
    position: relative;
    top: 15rem;
    margin: 0 auto;
    width: 30vw;
    height: 30vh;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    justify-content: center;
    align-items: center;
}

#user-details {
    font-size: 1.5rem;
}

#btn-logout {
    margin-top: 3rem;
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: transparent;
    font-size: 1.5rem;
    cursor: pointer;
}
</style>
