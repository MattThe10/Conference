<template>
    <div class="modal-backdrop" v-if="university">
        <div class="modal">

            <div class="modal-header">
                <div class="modal-title">
                    Kontrola
                </div>
                <button type="button" class="btn-close" @click="close" />
            </div>

            <div class="modal-body">
                <div class="question">
                    Naozaj chcete odstrániť túto univerzitu?
                </div>
                <div class="data">
                    {{ university.name }}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-submit" @click="submit">
                    Áno
                </button>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            university: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            submit() {
                axios.delete(`/api/universities/${this.university.id}`)
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri odstraňovaní univerzity: ", error);

                    if (this.university.faculties.length > 0) {
                        alert("Táto univerzita má priradené fakulty.");
                    } else {
                        alert("Nepodarilo sa odstrániť univerzitu.");
                    }
                });
            },
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
        padding: 32px 0;
    }

    .modal-body .question {
        font-size: 1.2rem;
        margin-bottom: 16px;
    }

    .modal-body .data {
        font-size: 1.5rem;
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
