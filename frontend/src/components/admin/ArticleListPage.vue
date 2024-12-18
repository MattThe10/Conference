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
                <tr v-for="article in articles" :key="article.id">
                    <td>
                        {{ article.id }}
                    </td>
                    <td>
                        {{ article.title }}
                    </td>
                    <td>
                        {{ article.conference.start_year }} / {{ article.conference.end_year }}
                    </td>
                    <td>
                        {{ article.article_status.name }}
                    </td>
                    <td class="operations">
                        <button>
                            Upraviť
                        </button>
                        <button @click="openArticleForReviewerEditModal(article)">
                            Prideliť
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

        <div v-if="selectedItem !== null" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <form @submit.prevent="setArticleToReviewer">
                    <div class="article-title">
                        <span class="left">
                            Príspevok
                        </span>
                        <span class="right">
                            {{ selectedItem.title  }}
                        </span>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" v-model="email">
                    </div>
                    <div>
                        <input type="hidden" v-model="user_id" v-value="selectedItem.id">
                    </div>
                    <div>
                        <button type="submit" class="default-button">Potvrdiť</button>
                    </div>
                </form>
            </div>
        </div>

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
            articles: [],
            article_id: '',
            email: '',
            users: [],
            selectedItem: null,
        }
    },
    methods: {
        openArticleForReviewerEditModal(article) {
            this.selectedItem = article;
            this.article_id = article.id;
        },
        closeModal() {
            this.selectedItem = null;
        },
        async getData() {
            const articles_response = await axios.get("/api/articles");
            this.articles = articles_response.data;

            const users_response = await axios.get("/api/users");
            this.users = users_response.data;
        },
        async setArticleToReviewer() {
            try {
                let user_id = null;
                this.users.forEach(user => {
                    if (user.email == this.email) {
                        user_id = user.id;
                    }
                });console.log(this.article_id); console.log(user_id);

                await axios.post('/api/reviews',{
                    article_id: this.article_id,
                    user_id: user_id,
                });

                console.log('Successfull store');

                window.location.reload();
            } catch (error) {
                console.log('Update error: ', error);

                if (error.response) {
                    if (error.response.data.errors) {
                        this.errorMessages = Object.values(error.response.data.errors).flat();
                    } else {
                        this.errorMessages = [error.response.data.message] || ['Uknown error'];
                    }
                }
            }
        }
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

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
    }

    .input-group,
    .select-group,
    .article-title {
        display: flex;
        margin-bottom: 16px;
    }

    .input-group label,
    .select-group label,
    .article-title .left {
        flex: 2;
        align-content: center;
        text-align: left;
    }

    .input-group input,
    .select-group select,
    .article-title .right {
        height: 4vh;
        flex: 1;
        align-content: center;
        margin-left: 1vw;
    }

    .default-button {
        height: 5vh;
        width: 10vh;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
    }
</style>
