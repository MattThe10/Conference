<template>
    <div>
        <NavBar></NavBar>
        <div v-if="['student', 'review'].includes(role.key)">
            <h1 class="conference-header">Zoznam konferencií</h1>
            <div class="conference-wrapper">
                <div class="conference-display">
                    <ConferenceItem v-for="conference in conferences" :key="conference.id" class="conference-item"
                        :conference="conference" @openForm="openForm" @openDetails="openDetails"></ConferenceItem>
                </div>
                <div v-if="selectedConferenceForm" class="modal-backdrop conference-button" @click="closeForm">
                    <div class="modal-content" @click.stop>
                        <button class="close-btn" @click="closeForm">✖</button>
                        <ArticlesForm :conference="selectedConferenceForm" @close="closeForm" />
                    </div>
                </div>
                <div v-if="selectedConferenceDetails" class="modal-backdrop conference-button" @click="closeDetails">
                    <div class="modal-content" @click.stop>
                        <button class="close-btn" @click="closeDetails">✖</button>
                        <ConferenceData :conference="selectedConferenceDetails" @openForm="openForm" @close="closeDetails" />
                    </div>
                </div>
            </div>
        </div>
        <div id="user-wrapper">
            <div id="user-details">
                <div class="user-details-wrapper">
                    <div class="user-details-wrapper-left">
                        <p>Rola: </p>
                        <p>Celé meno: </p>
                        <p>Email: </p>
                        <div v-if="role.key == 'student' || role.key == 'reviewer'">
                            <p>Univerzita: </p>
                        </div>
                        <div v-if="role.key == 'student' || role.key == 'reviewer'">
                            <p>Fakulta: </p>
                        </div>
                    </div>
                    <div class="user-details-wrapper-right">
                        <p>{{ role.name }}</p>
                        <div style="display: flex; flex-direction: row;">
                            <p>{{ user.name }}</p>
                            <p>{{ user.surname }}</p>
                        </div>
                        <p> {{ user.email }}</p>
                        <div v-if="role.key == 'student' || role.key == 'reviewer'">
                            <p>{{ university.name }}</p>
                        </div>
                        <div v-if="role.key == 'student' || role.key == 'reviewer'">
                            <p>{{ faculty.name }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <button @click="logout()" id="btn-logout" class="btn">Odhlásiť sa</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import NavBar from "./NavBar.vue";
import ConferenceItem from '@/components/ConferenceItem.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'
import ConferenceData from '@/components/ConferenceData.vue'

export default {
    components: {
        NavBar,
        ConferenceItem,
        ArticlesForm,
        ConferenceData
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
            selectedConferenceForm: null,
            selectedConferenceDetails: null,
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
                    `/api/faculties/${this.user.faculties_id}`
                );
                this.faculty = faculty_response.data;

                const university_response = await axios.get(
                    `/api/universities/${this.faculty.universities_id}`
                );
                this.university = university_response.data;
            } catch (error) {
                console.log("Error loading user data: ", error);
            }
        },
        async getConferences() {
            const conferences_response = await axios.get("/api/conferences");
            this.conferences = conferences_response.data;

            this.conferences = conferences_response.data.map(element => ({
                id: element['id'],
                name: element['title'],
                abstract: element['abstract'],
                keywords: element['keywords'],
                place: element['university']['name'],
                street: element['university']['address'],
                city: element['university']['city'],
                postal_code: element['university']['postal_code'],
                conference_date_sk: new Date(element['conference_date']).toLocaleDateString('sk-SK', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }),
                submission_deadline_sk: new Date(element['submission_deadline']).toLocaleDateString('sk-SK', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }),
                conference_date: new Date(element['conference_date']),
                submission_deadline: new Date(element['submission_deadline']),
            }));
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
        async storeArticle(conference_id) {
            try {
                console.log(conference_id);
                await axios.post('/api/articles', {
                    user_id: this.user.id,
                    conference_id: conference_id,
                });

                console.log('Successfull store');
            } catch (error) {
                console.log('Store error: ', error);

                if (error.response) {
                    if (error.response.data.errors) {
                        this.errorMessages = Object.values(error.response.data.errors).flat();
                    } else {
                        this.errorMessages = [error.response.data.message] || ['Uknown error'];
                    }
                }
            }
        },
        openDetails(conference) {
            this.selectedConferenceDetails = conference;
        },
        closeDetails() {
            this.selectedConferenceDetails = null;
        },
        openForm(conference) {
            this.storeArticle(conference.id);

            this.selectedConferenceForm = conference;
            this.selectedConferenceDetails = null;
        },
        closeForm() {
            this.selectedConferenceForm = null;
            window.location.reload();
        },
    },
    mounted() {
        this.getUser();
        this.getConferences();
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
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

#user-details p {
    font-size: 1rem;
    margin: 0 5px;
}

.user-details-wrapper {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 2rem;
}

.user-details-wrapper-left {
    font-weight: bold;
}

.user-details-wrapper-left,
.user-details-wrapper-right {
    text-align: left;
}

#btn-logout {
    padding: 10px;
    font-size: 1.2rem;
    margin-top: 2rem;
    width: 10rem;
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

.btn-close {
    position: relative;
    top: 2.7vh;
    left: 12.2vw;
}

.conference-display {
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    width: 100vw;
}

.conference-item {
    margin: 15px 0;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 10px;
}
</style>
