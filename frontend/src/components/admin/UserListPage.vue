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
                    Email
                </th>
                <th>
                    Meno
                </th>
                <th>
                    Priezvisko
                </th>
                <th>
                    Rola
                </th>
                <th>
                    Operácie
                </th>
            </tr>
            <div class="data-list">
                <tr v-for="user in users" :key="user.id">
                    <td>
                        {{ user.id }}
                    </td>
                    <td>
                        {{ user.email }}
                    </td>
                    <td>
                        {{ user.name }}
                    </td>
                    <td>
                        {{ user.surname }}
                    </td>
                    <td>
                        {{ user.role.name }}
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

        <button class="add-button">
            +
        </button>

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
            users: [],
        }
    },
    methods: {
        async getData() {
            const users_response = await axios.get("/api/users");
            this.users = users_response.data;
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
</style>
