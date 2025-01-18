<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <div>
            <table class="data-table">
                <tr>
                    <th>
                        Select
                    </th>
                    <th>
                        ID
                    </th>
                    <th>
                        Názov
                    </th>
                    <th>
                        Konferencia
                    </th>
                    <th>
                        Stav
                    </th>
                    <th>
                        Operácie
                    </th>
                </tr>
                <div class="data-list">
                    <tr v-for="article in articles" :key="article.id" @click="showDetailsModal(article)">
                        <td>
                            <input type="checkbox" class="checkbox" :value="article.id" v-model="selectedArticles" @click.stop>
                        </td>
                        <td>
                            {{ article.id }}
                        </td>
                        <td>
                            {{ article.title }}
                        </td>
                        <td>
                            Konferencia {{ article.conference.start_year }} / {{ article.conference.end_year }}
                        </td>
                        <td>
                            {{ article.article_status.name }}
                        </td>
                        <td class="operations">
                            <button @click.stop="showEditModal(article)">
                                Upraviť
                            </button>
                            <button @click.stop="showDeleteModal(article)">
                                Odstrániť
                            </button>
                        </td>
                    </tr>
                </div>
            </table>

            <!-- <button type="button" class="round-button add-button" @click="showCreateModal">
                +
            </button> -->
            <button type="button" class="round-button download-button" @click="downloadFiles" />
            <button type="button" class="round-button review-button" @click="showAddForReviewModal" />
        </div>
        
        <AddForReviewModal
            v-show="isAddForReviewModalVisible"
            :selectedArticles="selectedArticles"
            @close="closeAddForReviewModal" />

        <EditModal
            v-show="isEditModalVisible"
            :article="selectedData"
            @close="closeEditModal" />

        <DeleteModal
            v-show="isDeleteModalVisible"
            :article="selectedData"
            @close="closeDeleteModal" />

        <DetailsModal
            v-show="isDetailsModalVisible"
            :article="selectedData"
            @close="closeDetailsModal" />
    </div>
</template>

<script>
import axios from "axios";
import NavBar from '../../NavBar.vue'
import AdminBar from '../AdminBar.vue'
import AddForReviewModal from './AddForReviewModal.vue'
import EditModal from './EditModal.vue'
import DeleteModal from './DeleteModal.vue'
import DetailsModal from './DetailsModal.vue'

export default {
    components: {
        NavBar,
        AdminBar,
        AddForReviewModal,
        EditModal,
        DeleteModal,
        DetailsModal,
    },
    data() {
        return {
            articles: [],
            selectedArticles: [],
            isAddForReviewModalVisible: false,
            isEditModalVisible: false,
            isDeleteModalVisible: false,
            isDetailsModalVisible: false,
            selectedData: null,
        }
    },
    methods: {
        async getData() {
            const articles_response = await axios.get("/api/articles");
            this.articles = articles_response.data;
        },
        showAddForReviewModal() {
            this.isAddForReviewModalVisible = true;
        },
        closeAddForReviewModal() {
            this.isAddForReviewModalVisible = false;
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
        downloadFiles() {
            axios({
                url: `${process.env.VUE_APP_BACKEND_URL}/api/articles/download`,
                method: 'POST',
                responseType: 'blob',
                data: { article_ids: this.selectedArticles }
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'article.zip');
                document.body.appendChild(link);
                link.click();
            });
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
        padding: 0 16px;
    }

    .data-table .data-list .checkbox {
        width: 32px;
        height: 32px;
    }

    .round-button {
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

    .round-button.download-button::after {
        content: "\2193";
    }

    .round-button.review-button {
        right: 12vh;
    }

    .round-button.review-button::after {
        content: "+";
    }
</style>