<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <div class="list-content">
            <div class="list-header">
                <div class="list-title">
                    <h2>
                        Recenzie
                    </h2>
                    <button class="back-button" @click="back()">Späť</button>
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
                        Recenzent
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Stav recenzie
                    </th>
                    <th>
                        Operácie
                    </th>
                </tr>
                <div class="data-list">
                    <tr v-for="review in reviews" :key="review.id" @click="showDetailsModal(review)">
                        <td>
                            {{ review.id }}
                        </td>
                        <td>
                            {{ review.user.name }} {{ review.user.surname }} (ID: {{ review.user.id }})
                        </td>
                        <td>
                            {{ review.user.email }}
                        </td>
                        <td>
                            {{ review.pro != null && review.con != null && review.comment != null ? 'Hotová' : 'Prázdna' }}
                        </td>
                        <td class="operations">
                            <button @click.stop="showEditModal(review)">
                                Upraviť
                            </button>
                            <button @click.stop="showDeleteModal(review)">
                                Odstrániť
                            </button>
                        </td>
                    </tr>
                </div>
            </table>

        </div>

        <EditModal
            v-show="isEditModalVisible"
            :review="selectedData"
            @close="closeEditModal" />

        <DeleteModal
            v-show="isDeleteModalVisible"
            :review="selectedData"
            @close="closeDeleteModal" />

        <DetailsModal
            v-show="isDetailsModalVisible"
            :review="selectedData"
            @close="closeDetailsModal" />
    </div>
</template>

<script>
import axios from "axios";
import NavBar from '../../NavBar.vue'
import AdminBar from '../AdminBar.vue'
import EditModal from './EditModal.vue'
import DeleteModal from './DeleteModal.vue'
import DetailsModal from './DetailsModal.vue'

export default {
    components: {
        NavBar,
        AdminBar,
        EditModal,
        DeleteModal,
        DetailsModal,
    },
    data() {
        return {
            id: this.$route.params.id,

            reviews: [],
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
            const article_response = await axios.get(`api/articles/${ this.id }`);
            const article = article_response.data;
            this.reviews = article.reviews;
        },
        async searchData() {
            const reviews_response = await axios.get(`/api/reviews?search=${ this.search }`);
            this.reviews = reviews_response.data;
        },
        back() {
            this.$router.push({ name: 'ArticleDataList' });
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

    .list-header .list-title {
        display: flex;
    }

    .list-header .list-title h2 {
        display: flex;
        align-items: center;
    }

    .list-header .list-title .back-button {
        height: 40px;
        width: 10vh;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
        margin-left: 16px;
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