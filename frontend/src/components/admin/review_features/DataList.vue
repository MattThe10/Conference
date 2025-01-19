<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <div>
            <table class="data-table">
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Obsah
                    </th>
                    <th>
                        Aktívna
                    </th>
                    <th>
                        Operácie
                    </th>
                </tr>
                <div class="data-list">
                    <tr v-for="review_feature in review_features" :key="review_feature.id" @click="showDetailsModal(review_feature)">
                        <td>
                            {{ review_feature.id }}
                        </td>
                        <td>
                            {{ review_feature.content }}
                        </td>
                        <td>
                            {{ review_feature.is_active == 1 ? 'Áno' : 'Nie' }}
                        </td>
                        <td class="operations">
                            <button @click.stop="showEditModal(review_feature)">
                                Upraviť
                            </button>
                            <button @click.stop="showDeleteModal(review_feature)">
                                Odstrániť
                            </button>
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
            :review_feature="selectedData"
            @close="closeEditModal" />

        <DeleteModal
            v-show="isDeleteModalVisible"
            :review_feature="selectedData"
            @close="closeDeleteModal" />

        <DetailsModal
            v-show="isDetailsModalVisible"
            :review_feature="selectedData"
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
            review_features: [],
            isCreateModalVisible: false,
            isEditModalVisible: false,
            isDeleteModalVisible: false,
            isDetailsModalVisible: false,
            selectedData: null,
        }
    },
    methods: {
        async getData() {
            const review_features_response = await axios.get("/api/review_features");
            this.review_features = review_features_response.data;
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
    .data-table {
        display: flex;
        flex-direction: column;
        position: relative;
        left: 5rem;
        margin: 0 auto;
        width: 75vw;
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