<template>
    <div class="modal-backdrop" v-if="user">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Uprav používateľa
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <label for="email">
                            Email
                        </label>
                        <input type="email" id="email" v-model="email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">
                            Heslo
                        </label>
                        <input type="password" id="password" v-model="password">
                    </div>

                    <div class="input-group">
                        <label for="name">
                            Meno
                        </label>
                        <input type="text" id="name" v-model="name" required>
                    </div>

                    <div class="input-group">
                        <label for="surname">
                            Priezvisko
                        </label>
                        <input type="text" id="surname" v-model="surname" required>
                    </div>

                    <div class="select-group">
                        <label for="faculty">
                            Škola
                        </label>
                        <select id="faculty" v-model="facultyId" required>
                            <optgroup v-for="university in universities" :key="university.id" :label="university.name">
                                <option v-for="faculty in university.faculties" :key="faculty.id" :value="faculty.id">
                                    {{ faculty.name }}
                                </option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="select-group">
                        <label for="role">
                            Rola
                        </label>
                        <select id="role" v-model="roleId" required>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
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
                email: null,
                password: null,
                name: null,
                surname: null,
                facultyId: null,
                roleId: null,

                universities: null,
                roles: null,
            }
        },
        props: {
            user: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            async getData() {
                const universities_response = await axios.get("/api/universities");
                this.universities = universities_response.data;

                const roles_response = await axios.get("/api/roles");
                this.roles = roles_response.data;
            },
            submit() {
                axios.put(`/api/users/${this.user.id}`, {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    faculty_id: this.facultyId,
                    role_id: this.roleId,
                    new_password: this.password,
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri aktualizácii používateľa: ", error);
                    alert("Nepodarilo sa aktualizovať používateľa.");
                });
            },
        },
        mounted() {
            this.getData();
        },
        watch: {
            user: {
                immediate: true,
                handler (newUser) {
                    if (newUser) {
                        this.email = this.user.email;
                        this.name = this.user.name;
                        this.surname = this.user.surname;
                        this.facultyId = this.user.faculties_id;
                        this.roleId = this.user.roles_id;
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
