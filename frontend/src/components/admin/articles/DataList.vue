<template>
    <div>
        <NavBar></NavBar>
        <AdminBar></AdminBar>

        <div class="list-content">
            <div class="list-header">
                <div class="list-title">
                    <h2>
                        Príspevky
                    </h2>
                    <button class="back-button" @click="back()" v-show="id != null">Späť</button>
                </div>
                <div class="list-search" v-show="id === null">
                    <input type="text" v-model="search">
                    <button type="button" @click="searchData()">
                        Hľadaj
                    </button>
                </div>
            </div>
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
                            {{ article.conference.title }}
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
            <button type="button" class="round-button download-button" :class="{ disabled: isDownloadButtonDisabled(selectedArticles) }" @click="downloadFiles" />
            <button type="button" class="round-button review-button" :class="{ disabled: isAddForReviewButtonDisabled(selectedArticles) }" @click="showAddForReviewModal" v-if="id == null" />
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
            id: this.$route.params.id ?? null,
            articles: [],
            selectedArticles: [],
            isAddForReviewModalVisible: false,
            isEditModalVisible: false,
            isDeleteModalVisible: false,
            isDetailsModalVisible: false,
            selectedData: null,
            search: null,
        }
    },
    methods: {
        async getData() {
            if (this.id) {
                const conference_response = await axios.get(`/api/conferences/${ this.id }`);
                const conference = conference_response.data;
                this.articles = conference.articles;
            } else {
                const articles_response = await axios.get("/api/articles");
                this.articles = articles_response.data;
            }console.log(this.id);
        },
        async searchData() {
            const articles_response = await axios.get(`/api/articles?search=${ this.search }`);
            this.articles = articles_response.data;
        },
        back() {
            this.$router.push({ name: 'ConferenceDataList' });
        },
        isDownloadButtonDisabled(selected_articles) {
            if (selected_articles.length == 0) return true;

            let articles_with_documents = 0;

            this.articles.forEach(element => {
                if (selected_articles.includes(element.id)) {
                    if (element.documents.length >= 2) articles_with_documents++;
                }
            });

            if (articles_with_documents == 0) return true;
            else false;
        },
        isAddForReviewButtonDisabled(selected_articles) {
            if (selected_articles.length == 0) return true;

            return false;
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

    .round-button.disabled {
        opacity: .5;
        pointer-events: none;
    }
</style>