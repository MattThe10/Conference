<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <table class="data-table">
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Konferencia
                </th>
                <th>
                    Dátum
                </th>
                <th>
                    Deadline
                </th>
                <th>
                    Miesto
                </th>
                <th>
                    Operacie
                </th>
            </tr>
            <div class="data-list">
                <tr v-for="conference in conferences" :key="conference.id">
                    <td>
                        {{ conference.id }}
                    </td>
                    <td>
                        {{ conference.start_year }} / {{ conference.end_year }}
                    </td>
                    <td>
                        {{ new Date(conference.conference_date).toLocaleDateString('sk-SK', { year: 'numeric', month: '2-digit', day: '2-digit'}) }}
                    </td>
                    <td>
                        {{ new Date(conference.submission_deadline).toLocaleDateString('sk-SK', { year: 'numeric', month: '2-digit', day: '2-digit'}) }}
                    </td>
                    <td>
                        {{ conference.university.name }}
                    </td>
                    <td class="operations">
                        <button>
                            Upraviť
                        </button>
                        <!-- <button>
                            Odstrániť
                        </button> -->
                    </td>
                </tr>
            </div>
        </table>

        <button class="add-button" @click="openModal()">
            +
        </button>

        <div v-if="visible" class="modal-overlay" @click="closeOnOverlayClick">
            <div class="modal-content" @click.stop>
                <button class="close-button" @click="close">&times;</button>
                <form @submit.prevent="storeConference">
                    <div class="input-group">
                        <label for="conference-date">Dátum konferencie</label>
                        <input type="date" id="conference-date" v-model="conference_date">
                    </div>
                    <div class="input-group">
                        <label for="deadline-date">Deadline</label>
                        <input type="date" id="deadline-date" v-model="submission_deadline">
                    </div>
                    <div class="select-group">
                        <label for="university">Miesto konania</label>
                        <select id="university" v-model="location_id">
                            <option v-for="university in universities" :key="university.id" :value="university.id">
                                {{ university.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="default-button">Uložiť</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import NavBar from '../NavBar.vue'
import AdminBar from './AdminBar.vue'

export default {
    components: {
        NavBar,
        AdminBar
    },
    data() {
        return {
            visible: false,
            conferences: [],
            universities: [],
            conference_date: '',
            submission_deadline: '',
            location_id: '',
        }
    },
    methods: {
        openModal() {
            this.visible = true;
        },
        close() {
            this.visible = false;
        },
        closeOnOverlayClick() {
            this.close();
        },
        async getData() {
            const conferences_response = await axios.get("/api/conferences");
            this.conferences = conferences_response.data;

            const universities_response = await axios.get("/api/universities");
            this.universities = universities_response.data;
        },
        async storeConference() {
            try {
                let conference_date     = new Date(this.conference_date);
                let submission_deadline = new Date(this.submission_deadline);

                let start_year = new Date(conference_date);
                start_year.setFullYear(conference_date.getFullYear() - 1);

                await axios.post('/api/conferences/store',{
                    conference_date:        conference_date.toISOString().split('T')[0], 
                    submission_deadline:    submission_deadline.toISOString().split('T')[0], 
                    location_id:            this.location_id,
                    start_year:             start_year.getFullYear(),
                    end_year:               conference_date.getFullYear(),
                });

                console.log('Successfull store');

                window.location.reload();
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
        this.getData();
    },
};
</script>

<style scoped>
    .data-table {
        display: flex;
        flex-direction: column;
        position: relative;
        left: 5rem;
        margin: 0 auto;
        width: 80vw;
        height: 80vh;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        align-items: center;
    }

    .data-table tr {
        display: flex;
    }

    .data-table tr:hover {
        background-color: #ccc;
    }

    .data-table th,
    .data-table td {
        width: 12vw;
        height: 6vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .data-table .operations {
        display: flex;
        gap: 1vw;
    }

    .data-table .operations button {
        height: 5vh;
        width: 10vh;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
    }

    .data-table .data-list {
        max-height: 72vh;
        overflow-y: auto;
    }

    .add-button {
        height: 10vh;
        width: 10vh;
        position: absolute;
        bottom: 2vh;
        right: 2vh;
        border-radius: 50%;
        background-color: #52b69a;
        border: none;
        color: #fefae0;
        font-size: 1.5rem;
    }

    .modal-overlay {
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
        padding: 50px 30px 30px;
        border-radius: 8px;
        position: relative;
    }

    .close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .input-group,
    .select-group {
        display: flex;
        margin-bottom: 16px;
    }

    .input-group label,
    .select-group label {
        flex: 2;
        align-content: center;
        text-align: left;
    }

    .input-group input,
    .select-group select {
        height: 4vh;
        flex: 1;
        margin-left: 1vw;
    }

    .default-button {
        height: 5vh;
        width: 10vh;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
    }
</style>
