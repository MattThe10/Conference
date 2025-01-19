<template>
    <div class="modal-backdrop" v-if="university">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Uprav univerzitu
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <label for="name">
                            Názov
                        </label>
                        <input type="text" id="name" v-model="name" required>
                    </div>

                    <div class="input-group">
                        <label for="address">
                            Adresa
                        </label>
                        <input type="text" id="address" v-model="address" required>
                    </div>

                    <div class="input-group">
                        <label for="city">
                            Mesto
                        </label>
                        <input type="text" id="city" v-model="city" required>
                    </div>

                    <div class="input-group">
                        <label for="postal_code">
                            PSČ
                        </label>
                        <input type="text" id="postal_code" v-model="postalCode">
                    </div>

                    <div class="input-group">
                        <label for="country">
                            Krajina
                        </label>
                        <input type="text" id="country" v-model="country" required>
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
                name: null,
                address: null,
                city: null,
                postalCode: null,
                country: null,
            }
        },
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
                axios.put(`/api/universities/${this.university.id}`, {
                    name: this.name,
                    address: this.address,
                    city: this.city,
                    postal_code: this.postalCode,
                    country: this.country,
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri aktualizácii univerzity: ", error);
                    alert("Nepodarilo sa aktualizovať univerzitu.");
                });
            },
        },
        watch: {
            university: {
                immediate: true,
                handler (newUniversity) {
                    if (newUniversity) {
                        this.name = this.university.name;
                        this.address = this.university.address;
                        this.city = this.university.city;
                        this.postalCode = this.university.postal_code;
                        this.country = this.university.country;
                    }
                }
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
