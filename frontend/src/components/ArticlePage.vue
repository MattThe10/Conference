<template>
    <NavBar></NavBar>
    <h2 class="articles-header">Moje príspevky</h2>
    <div id="articles-wrapper">
        <div id="articles-display">
            <ArticleItem v-for="article in articles" :key="article.id" :article="article" @openDetails="openDetails" />
            <!-- Modal for Article Details -->
            <div v-if="selectedArticle" class="modal-backdrop" @click="closeDetails">
                <div class="modal-content" @click.stop>
                    <button class="close-btn" @click="closeDetails">✖</button>
                    <ArticleDetails :article="selectedArticle" @openReviewForm="openReviewForm" />
                </div>
            </div>

            <!-- Modal for Review Form -->
            <div v-if="showReviewForm" class="modal-backdrop" @click="closeReviewForm">
                <div class="modal-content" @click.stop>
                    <button class="close-btn" @click="closeReviewForm">✖</button>
                    <ReviewForm :article="selectedArticle" @close="closeReviewForm" />
                </div>
            </div>
        </div>
    </div>
    <div id="articles-button"> </div>
</template>
<script>
import axios from "axios";
import NavBar from '@/components/NavBar.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'
import ArticleItem from '@/components/ArticleItem.vue'
import ArticleDetails from '@/components/ArticleDetails.vue'
import ReviewForm from '@/components/ReviewForm.vue'


export default {
    components: {
        NavBar,
        ArticlesForm,
        ArticleItem,
        ArticleDetails,
        ReviewForm
    },
    data() {
        return {
            user: [],
            articles: [
                //Sem sa pridavaju articles
                { id: 1, title: 'Vue 3 Basics', author: 'Jan Tenky', date: '2025-02-10', reviews: [] },
                { id: 2, title: 'Advanced Vue Techniques', author: 'Petra Hladka', date: '2025-11-03', reviews: [] },
            ],
            selectedArticle: null,
            showReviewForm: false,
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
        openForm(conference) {
            this.selectedConference = conference;
        },
        closeForm() {
            this.selectedConference = null;
        },
        openDetails(article) {
            this.selectedArticle = article;
        },
        closeDetails() {
            this.selectedArticle = null;
        },
        openReviewForm() {
            this.showReviewForm = true;
        },
        closeReviewForm() {
            this.showReviewForm = false;
        },
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
    margin-bottom: 15px;
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

#articles-button {}

#btn-insert {
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
    position: relative;
    left: 13.3%;

}

/* Modal styles (reuse from previous example) */
.modal-backdrop {
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
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: transparent;
    font-size: 1.5rem;
    cursor: pointer;
}
</style>