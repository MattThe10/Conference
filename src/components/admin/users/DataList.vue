<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <div class="list-content">
            <div class="list-header">
                <div class="list-title">
                    <h2>
                        Používatelia
                    </h2>
                </div>
                <div class="list-search">
                    <input type="text" v-model="search">
                    <button type="button" @click="searchData()">
                        Hľadaj
                    </button>
                </div>
            </div>
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
                    <tr v-for="user in users" :key="user.id" @click="showDetailsModal(user)">
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
                            <button @click.stop="showEditModal(user)">
                                Upraviť
                            </button>
                            <!-- <button @click.stop="showDeleteModal(user)">
                                Odstrániť
                            </button> -->
                        </td>
                    </tr>
                </div>
            </table>

            <button type="button" class="add-button" @click="showCreateModal">
                +
            </button>
        </div>
        
        <CreateModal
            v-show="isCreateModalVisible"
            @close="closeCreateModal" />

        <EditModal
            v-show="isEditModalVisible"
            :user="selectedData"
            @close="closeEditModal" />

        <DeleteModal
            v-show="isDeleteModalVisible"
            :user="selectedData"
            @close="closeDeleteModal" />

        <DetailsModal
            v-show="isDetailsModalVisible"
            :user="selectedData"
            @close="closeDetailsModal" />
    </div>
</template>

<script>
import axios from "axios";
import NavBar from '../../NavBar.vue'
import AdminBar from '../AdminBar.vue'
import CreateModal from './CreateModal.vue'
import EditModal from './EditModal.vue'
import DeleteModal from './DeleteModal.vue'
import DetailsModal from './DetailsModal.vue'

export default {
    components: {
        NavBar,
        AdminBar,
        CreateModal,
        EditModal,
        DeleteModal,
        DetailsModal,
    },
    data() {
        return {
            current_user: null,
            users: [],
            isCreateModalVisible: false,
            isEditModalVisible: false,
            isDeleteModalVisible: false,
            isDetailsModalVisible: false,
            selectedData: null,
            search: null,
        }
    },
    methods: {
        async getData() {
            const users_response = await axios.get("/api/users");
            this.users = users_response.data;

            const current_user_response = await axios.get("/api/current_user");
            this.current_user = current_user_response.data;

            this.users = this.users.filter(user => {
                return user.id != this.current_user.id;
            });

            if (this.current_user.role.key != 'super_admin') {
                this.users = this.users.filter(user => {
                    return user.role.key != 'super_admin';
                });
            }

            if (this.current_user.role.key == 'admin') {
                this.users = this.users.filter(user => {
                    return ['admin', 'super_admin'].includes(user.role.key);
                });
            }
        },
        async searchData() {
            const users_response = await axios.get(`/api/users?search=${ this.search }`);
            this.users = users_response.data;
        },
        showCreateModal() {
            this.isCreateModalVisible = true;
        },
        closeCreateModal() {
            this.isCreateModalVisible = false;
        },
        showEditModal(data) {
            this.selectedData = data;
            this.isEditModalVisible = true;
        },
        closeEditModal() {
            this.isEditModalVisible = false;
            this.selectedData = null;
        },
        showDeleteModal(data) {
            this.selectedData = data;
            this.isDeleteModalVisible = true;
        },
        closeDeleteModal() {
            this.isDeleteModalVisible = false;
            this.selectedData = null;
        },
        showDetailsModal(data) {
            this.selectedData = data;
            this.isDetailsModalVisible = true;
        },
        closeDetailsModal() {
            this.isDetailsModalVisible = false;
            this.selectedData = null;
        },
    },
    mounted() {
        this.getData();
    },
};
</script>

<style scoped>
    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-left: 16px;
    }

    .list-header .list-search {
        display: flex;
        align-items: center;
    }

    .list-header .list-search input {
        height: 40px;
        border-radius: 12px;
        font-size: 1.2rem;
        padding: 8px;
    }

    .list-header .list-search button {
        height: 40px;
        width: 10vh;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
    }

    .list-content {
        padding: 0 5vw 0 16vw;
    }

    .data-table {
        display: flex;
        flex-direction: column;
        position: relative;
        /* left: 5rem; */
        margin: 0 auto;
        /* width: 75vw; */
        height: 80vh;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        align-items: center;
    }

    .data-table tr {
        display: flex;
    }

    .data-table .data-list tr:hover {
        background-color: #ccc;
        cursor: pointer;
    }

    .data-table th,
    .data-table td {
        width: 12vw;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 0;
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
        padding: 0 16px;
    }

    .add-button {
        height: 8vh;
        width: 8vh;
        position: absolute;
        bottom: 2vh;
        right: 2vh;
        border-radius: 50%;
        background-color: #52b69a;
        border: none;
        color: #fefae0;
        font-size: 3rem;
    }
</style>