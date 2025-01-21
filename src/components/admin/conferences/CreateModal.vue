<template>
    <div class="modal-backdrop">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Pridaj konferenciu
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <label for="title">
                            Názov
                        </label>
                        <input type="text" id="title" v-model="title" required>
                    </div>

                    <div class="textarea-group">
                        <label for="abstract">
                            Abstrakt
                        </label>
                        <textarea id="abstract" cols="30" rows="3" v-model="abstract" required />
                    </div>

                    <div class="select-group">
                        <label for="university">
                            Miesto konania
                        </label>
                        <select id="university" v-model="universityId" required>
                            <option v-for="university in universities" :key="university.id" :value="university.id">
                                {{ university.name }}
                            </option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="conference_date">
                            Dátum konferencie
                        </label>
                        <input type="date" id="conference_date" v-model="conferenceDate" required>
                    </div>

                    <div class="input-group">
                        <label for="submission_deadline">
                            Deadline odovzdania prác
                        </label>
                        <input type="date" id="submission_deadline" v-model="submissionDeadline" required>
                    </div>

                    <div class="input-group">
                        <label for="review_assignment_deadline">
                            Deadline na priradenie publikácie 
                        </label>
                        <input type="date" id="review_assignment_deadline" v-model="reviewAssignmentDeadline" required>
                    </div>

                    <div class="input-group">
                        <label for="review_submission_deadline">
                            Deadline na recenzovanie
                        </label>
                        <input type="date" id="review_submission_deadline" v-model="reviewSubmissionDeadline" required>
                    </div>

                    <div class="input-group">
                        <label for="review_publication_date">
                            Dátum zverejnenia recenzií
                        </label>
                        <input type="date" id="review_publication_date" v-model="reviewPublicationDate" required>
                    </div>

                    <div class="select-group">
                        <label for="is_active">
                            Aktívna
                        </label>
                        <select id="is_active" v-model="isActive" required>
                            <option value="1">
                                Áno
                            </option>
                            <option value="0">
                                Nie
                            </option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-submit">
                        Potvrď
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                title: null,
                abstract: null,
                universityId: null,
                conferenceDate: null,
                submissionDeadline: null,
                reviewAssignmentDeadline: null,
                reviewSubmissionDeadline: null,
                reviewPublicationDate: null,
                isActive: 1,

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
                    title: this.title,
                    abstract: this.abstract,
                    location_id: this.universityId,
                    conference_date: this.conferenceDate,
                    submission_deadline: this.submissionDeadline,
                    review_assignment_deadline: this.reviewAssignmentDeadline,
                    review_submission_deadline: this.reviewSubmissionDeadline,
                    review_publication_date: this.reviewPublicationDate,
                    is_active: this.isActive,
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
