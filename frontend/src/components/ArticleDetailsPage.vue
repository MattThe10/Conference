<template>
    <div>
        <NavBar></NavBar>
        <div>
            <h2>
                {{ article.title }}
            </h2>
            <div>
                Konferencia {{ article.conference?.start_year }} / {{ article.conference?.end_year }}
            </div>
            <div>
                {{ article.article_status?.name }}
            </div>
            <router-link :to="{ path: `/conferences/${article?.conference?.id}/articles/${article?.id}/update` }">Upraviť</router-link>
        </div>

        <table class="data-table">
            <tr>
                <th>
                    Recenzia
                </th>
                <th>
                    Pridelený
                </th>
                <th>
                    Upravený
                </th>
                <th>
                    Operácie
                </th>
            </tr>
            <div class="data-list">
                <tr v-for="review in reviews" :key="review.id">
                    <td>
                        #{{ review.id }}
                    </td>
                    <td>
                        {{ new Date(review.created_at).toLocaleDateString('sk-SK', { year: 'numeric', month: '2-digit', day: '2-digit'}) }}
                    </td>
                    <td>
                        {{ new Date(review.updated_at).toLocaleDateString('sk-SK', { year: 'numeric', month: '2-digit', day: '2-digit'}) }}
                    </td>
                    <td class="operations">
                        <router-link :to="{ path: '/articles/' + article_id + '/reviews/' + review.id }">
                            <button>
                                Detaily
                            </button>
                        </router-link>
                    </td>
                </tr>
            </div>
        </table>
    </div>
</template>

<script>
import axios from "axios";
import NavBar from "./NavBar.vue";

export default {
    components: {
        NavBar,
    },
    data() {
        return {
            article_id: null,
            article: '',
            reviews: [],
        };
    },
    methods: {
        async getData() {
            const article_response = await axios.get(`/api/articles/${this.article_id}`);
            this.article = article_response.data;

            this.article.reviews.forEach(review => {
                if (review.comment != null) {
                    this.reviews.push(review);
                }
            });
        },
    },
    mounted() {
        this.article_id = this.$route.params.article_id;

        this.getData();
    },
};
</script>

<style scoped>
#user-wrapper {
    display: flex;
    flex-direction: column;
    position: relative;
    top: 15rem;
    margin: 0 auto;
    width: 30vw;
    height: 30vh;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    justify-content: center;
    align-items: center;
}

#user-details {
    font-size: 1.5rem;
}

#btn-logout {
    margin-top: 3rem;
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
}

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
</style>
