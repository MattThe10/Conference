<template>
    <NavBar></NavBar>
    <h2 class="articles-header">Moje príspevky</h2>
    <div id="articles-wrapper">
        <div id="articles-display">
            <ul>
                <li v-for="article in articles" class="articles-li" :key="article.id" style="height: 100%;">
                    <span>{{ article.title }}</span>
                    <div style="font-size: 1.2rem;">
                        <div v-for="user in article.users" :key="user.id">
                            {{ user.name.charAt(0) }}.
                            {{ user.surname }}
                        </div>
                        <div>
                            Konferencia {{ article.conference.start_year }} / {{ article.conference.end_year }}
                        </div>
                        <div>
                            {{ article.article_status.name }}
                        </div>
                        <router-link to="#">Detaily</router-link>
                    </div>
                    <hr v-if="article.id !== 4">
                </li>
            </ul>
        </div>
    </div>
    <div id="articles-button">
        <button id="btn-insert" class="btn" @click="createArticle();">Vložiť príspevok</button>
        <ArticlesForm v-model:visible="showModal"></ArticlesForm>
    </div>
</template>
<script>
import axios from "axios";
import NavBar from '@/components/NavBar.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'

export default {
    components: {
        NavBar,
        ArticlesForm
    },
    data() {
        return {
            user: [],
            articles: [],
            showModal: false,
        }
    },
    methods: {
        async getData() {
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;

            const articles_response = await axios.get("/api/articles");
            const articles = articles_response.data;

            articles.forEach(article => {
                article['users'].forEach(user => {
                    if (user['id'] == this.user['id']) this.articles.push(article);
                });
            });
        },
        async createArticle() {
            try {
                // Request to create article
                await axios.post("/api/articles", {
                    'user_id': this.user.id,
                });

                // Show modal after successful creation
                this.showModal = true;
            } catch (error) {
                console.log("Article creation error: ", error);

                if (error.response) {
                    if (error.response.data.errors) {
                        this.errorMessages = Object.values(
                            error.response.data.errors
                        ).flat();
                    } else {
                        this.errorMessages = error.response.data.message || "Uknown error";
                    }
                }
            }
        }
    },
    mounted() {
        this.getData();
    },
}
</script>

<style scoped>
#articles-wrapper {
    display: flex;
    flex-direction: column;
    width: 40%;
    border: 2px solid black;
    margin: 0 auto;
    height: 100%;
}

.articles-header {
    font-size: 3rem;
}

#articles-display {
    font-size: 1.5rem;
}

.articles-li:first-child {
    margin-top: 1rem;
}

.articles-li {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    height: 4rem;
    margin-bottom: 1rem;
}

.articles-li-div {
    font-size: 1.2rem;
    display: flex;
    flex-direction: row;
    justify-content: center;
    gap: 1rem;
}

#btn-insert {
    padding: 5px;
    font-size: 2rem;
    position: relative;
    left: 13.3%;

}
</style>