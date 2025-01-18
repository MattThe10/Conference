<template>
    <div class="modal-backdrop">
        <div class="modal">

            <div class="modal-header">
                <div class="modal-title">
                    Pridaj konferenciu
                </div>
                <button type="button" class="btn-close" @click="close" />
            </div>

            <div class="modal-body">
                <div class="input-group">
                    <label for="start_year">
                        Začiatočný rok
                    </label>
                    <input type="number" id="start_year" v-model="startYear">
                </div>

                <div class="input-group">
                    <label for="end_year">
                        Konečný rok
                    </label>
                    <input type="number" id="end_year" v-model="endYear">
                </div>

                <div class="input-group">
                    <label for="conference_date">
                        Dátum konferencie
                    </label>
                    <input type="date" id="conference_date" v-model="conferenceDate">
                </div>

                <div class="input-group">
                    <label for="submission_deadline">
                        Deadline odovzdania prác
                    </label>
                    <input type="date" id="submission_deadline" v-model="submissionDeadline">
                </div>

                <div class="select-group">
                    <label for="university">
                        Miesto konania
                    </label>
                    <select id="university" v-model="universityId">
                        <option v-for="university in universities" :key="university.id" :value="university.id">
                            {{ university.name }}
                        </option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-submit" @click="submit">
                    Potvrď
                </button>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                startYear: null,
                endYear: null,
                conferenceDate: null,
                submissionDeadline: null,
                universityId: null,

                universities: null,
            }
        },
        methods: {
            close() {
                this.$emit('close');
            },
            async getData() {
                const universities_response = await axios.get("/api/universities");
                this.universities = universities_response.data;
            },
            submit() {
                axios.post("/api/conferences", {
                    start_year: this.startYear,
                    end_year: this.endYear,
                    conference_date: this.conferenceDate,
                    submission_deadline: this.submissionDeadline,
                    location_id: this.universityId,
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri vytváraní konferencie: ", error);
                    alert("Nepodarilo sa vytvoriť konferenciu.");
                });
            },
        },
        mounted() {
            this.getData();
        },
    };
</script>

<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        padding: 16px;
        border-radius: 12px;
        width: 30vw;
    }

    @media only screen and (max-width: 1200px) {
        .modal {
            width: 50vw;
        }
    }

    @media only screen and (max-width: 720px) {
        .modal {
            width: 80vw;
        }
    }

    @media only screen and (max-width: 440px) {
        .modal {
            width: 100vw;
            height: 100vh;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        font-size: 2rem;
    }

    .modal-header .modal-title {
        display: flex;
        align-items: center;
    }

    .modal-header .btn-close {
        width: 64px;
        height: 64px;
        font-size: 2rem;
        background-color: transparent;
        border: none;
    }

    .modal-header .btn-close::before {
        content: "\2715";
        font-weight: 700;
    }

    .modal-body {
        padding: 16px 0;
    }

    .modal-footer .btn-submit {
        width: 100%;
        height: 64px;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
        font-size: 1.2rem;
    }

    .input-group,
    .select-group {
        display: flex;
        flex-direction: column;
        text-align: left;
        gap: 8px;
        margin-bottom: 16px;
    }

    .input-group label,
    .select-group label {
        font-size: 1.2rem;
    }

    .input-group input,
    .select-group select {
        height: 40px;
        border-radius: 12px;
        font-size: 1.2rem;
        padding: 8px;
    }
</style>
