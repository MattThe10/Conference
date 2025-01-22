<template>
    <div>
        <NavBar></NavBar>
        <div>
            <h2 class="articles-header">Moje príspevky</h2>
            <div id="articles-wrapper">
                <div id="articles-display">
                    <ArticleItem v-for="article in articles" :key="article.id" :article="article" :source="'articles'"
                        @openDetails="openDetails" />
                    <!-- Modal for Article Details -->
                    <div v-if="selectedArticle" class="modal-backdrop" @click="closeDetails">
                        <div class="modal-content" @click.stop>
                            <button class="close-btn" @click="closeDetails">✖</button>
                            <ArticleDetails :article="selectedArticle" :source="selectedSource"
                                @openReviewForm="openReviewForm" @openArticleEditForm="openArticleEditForm"
                                @openReviewDetails="openReviewDetails" />
                        </div>
                    </div>

                    <!-- Modal for Review Form -->
                    <div v-if="showReviewForm" class="modal-backdrop" @click="closeReviewForm(selectedArticle)">
                        <div class="modal-content-review" @click.stop>
                            <button class="close-btn" @click="closeReviewForm(selectedArticle)">✖</button>
                            <ReviewForm :article="selectedArticle" @close="closeReviewForm(selectedArticle)" />
                        </div>
                    </div>

                    <!-- Modal for Article Edit Form -->
                    <div v-if="showArticleEditForm" class="modal-backdrop"
                        @click="closeArticleEditForm(selectedArticle)">
                        <div class="modal-content" @click.stop>
                            <button class="close-btn" @click="closeArticleEditForm(selectedArticle)">✖</button>
                            <ArticleEditForm :article="selectedArticle"
                                @close="closeArticleEditForm(selectedArticle)" />
                        </div>
                    </div>

                    <!-- Modal for Review Details -->
                    <div v-if="showReviewDetails" class="modal-backdrop" @click="closeReviewDetails(selectedArticle)">
                        <div class="modal-content-review" @click.stop>
                            <button class="close-btn" @click="closeReviewDetails(selectedArticle)">✖</button>
                            <ReviewDetails :review="selectedReview" @close="closeReviewDetails(selectedArticle)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="user.role?.key == 'reviewer'">
            <h2 class="articles-header">Pridelené príspevky</h2>
            <div id="articles-wrapper">
                <div id="articles-display">
                    <ArticleItem v-for="article in articlesForRevew" :key="article.id" :article="article"
                        :source="'articlesForReview'" @openDetails="openDetails" />
                    <!-- Modal for Article Details -->
                    <div v-if="selectedArticle" class="modal-backdrop" @click="closeDetails">
                        <div class="modal-content" @click.stop>
                            <button class="close-btn" @click="closeDetails">✖</button>
                            <ArticleDetails :article="selectedArticle" :source="selectedSource"
                                @openReviewForm="openReviewForm" @openArticleEditForm="openArticleEditForm"
                                @openReviewDetails="openReviewDetails" />
                        </div>
                    </div>

                    <!-- Modal for Review Form -->
                    <div v-if="showReviewForm" class="modal-backdrop" @click="closeReviewForm(selectedArticle)">
                        <div class="modal-content-review" @click.stop>
                            <button class="close-btn" @click="closeReviewForm(selectedArticle)">✖</button>
                            <ReviewForm :article="selectedArticle" @close="closeReviewForm(selectedArticle)" />
                        </div>
                    </div>

                    <!-- Modal for Article Edit Form -->
                    <div v-if="showArticleEditForm" class="modal-backdrop"
                        @click="closeArticleEditForm(selectedArticle)">
                        <div class="modal-content" @click.stop>
                            <button class="close-btn" @click="closeArticleEditForm(selectedArticle)">✖</button>
                            <ArticleEditForm :article="selectedArticle"
                                @close="closeArticleEditForm(selectedArticle)" />
                        </div>
                    </div>

                    <!-- Modal for Review Details -->
                    <div v-if="showReviewDetails" class="modal-backdrop" @click="closeReviewDetails(selectedArticle)">
                        <div class="modal-content-review" @click.stop>
                            <button class="close-btn" @click="closeReviewDetails(selectedArticle)">✖</button>
                            <ReviewDetails :review="selectedReview" @close="closeReviewDetails(selectedArticle)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="articles-button"> </div> -->
    </div>
</template>
<script>
import axios from "axios";
import NavBar from '@/components/NavBar.vue'
// import ArticlesForm from '@/components/ArticlesForm.vue'
import ArticleItem from '@/components/ArticleItem.vue'
import ArticleDetails from '@/components/ArticleDetails.vue'
import ReviewForm from '@/components/ReviewForm.vue'
import ArticleEditForm from '@/components/ArticleEditForm.vue'
import ReviewDetails from '@/components/ReviewDetails.vue'


export default {
    components: {
        NavBar,
        // ArticlesForm,
        ArticleItem,
        ArticleDetails,
        ReviewForm,
        ArticleEditForm,
        ReviewDetails
    },
    data() {
        return {
            user: [],
            articles: [
                //Sem sa pridavaju articles
                { id: 1, title: 'Vue 3 Basics', conference: 'Konferencia 1', status: 'Rozpracovaná', authors: [{name: 'Jan', surname: 'Tenky'}, {name: 'Petra', surname: 'Hladka'}], date: '2025-02-10', reviews: [] },
                { id: 2, title: 'Advanced Vue Techniques', conference: 'Konferencia 2', status: 'Rozpracovaná', authors: [{name: 'Petra', surname: 'Hladka'}], date: '2025-11-03', reviews: [] },
            ],
            articlesForRevew: [
                { id: 1, title: 'Vue 3 Basics', conference: 'Konferencia 1', status: 'Rozpracovaná', authors: [{name: 'Jan', surname: 'Tenky'}, {name: 'Petra', surname: 'Hladka'}], date: '2025-02-10', reviews: [] },
                { id: 2, title: 'Advanced Vue Techniques', conference: 'Konferencia 2', status: 'Rozpracovaná', authors: [{name: 'Petra', surname: 'Hladka'}], date: '2025-11-03', reviews: [] },
            ],
            selectedArticle: null,
            selectedSource: null,
            showReviewForm: false,
            showArticleEditForm: false,
            showReviewDetails: false,
        }
    },
    methods: {
        async getData() {
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;

            const articles_response = await axios.get("/api/articles");
            const articles = articles_response.data;

            this.articles = articles
                .filter(article => article['users'].some(user => user['id'] == this.user['id']))
                .map(element => ({
                    id: element['id'],
                    title: element['title'],
                    abstract: element['abstract'],
                    keywords: element['keywords'],
                    conference: element['conference']['title'],
                    status: element['article_status']['name'],
                    authors: element['users'],
                    date: new Date(element['conference']['submission_deadline']).toLocaleDateString('sk-SK', { year: 'numeric', month: 'long', day: 'numeric' }),
                    reviews: element['reviews'],
                }));

            this.articlesForRevew = articles.filter(article => {
                return new Date > new Date(article.conference.review_assignment_deadline);
            });

            this.articlesForRevew = this.articlesForRevew
                .filter(article => article['reviews'].some(review => review['users_id'] == this.user['id']))
                .map(element => ({
                    id: element['id'],
                    title: element['title'],
                    abstract: element['abstract'],
                    keywords: element['keywords'],
                    conference: element['conference']['title'],
                    status: element['article_status']['name'],
                    authors: element['users'],
                    date: new Date(element['conference']['review_submission_deadline']).toLocaleDateString('sk-SK', { year: 'numeric', month: 'long', day: 'numeric' }),
                    reviews: element['reviews'],
                }));
        },
        openForm(conference) {
            this.selectedConference = conference;
        },
        closeForm() {
            this.selectedConference = null;
        },
        openDetails(article, source) {
            console.log(article);
            this.selectedArticle = article;
            this.selectedSource = source;

            this.$router.push({
                name: 'ArticleDetails',
                params: { articleId: article.id },
            });
        },
        closeDetails() {
            this.selectedArticle = null;
            this.selectedSource = null;

            this.$router.push('/articles');
        },
        openReviewForm() {
            this.showReviewForm = true;
        },
        closeReviewForm() {
            this.showReviewForm = false;
        },
        openArticleEditForm(article) {
            this.showArticleEditForm = true;

            this.$router.push({
                name: 'ArticleEditForm',
                params: { articleId: article.id },
            });
        },
        closeArticleEditForm(article) {
            this.showArticleEditForm = false;

            this.$router.push({
                name: 'ArticleDetails',
                params: { articleId: article.id },
            });
        },
        openReviewDetails(article, review) {
            this.selectedReview = review;
            this.showReviewDetails = true;

            this.$router.push({
                name: 'ReviewDetails',
                params: { articleId: article.id, reviewId: review.id },
            });
        },
        closeReviewDetails(article) {
            this.selectedReview = null;
            this.showReviewDetails = false;

            this.$router.push({
                name: 'ArticleDetails',
                params: { articleId: article.id },
            });
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

/* #articles-button {} */

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




/* Pridané */
.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 40%;
    position: relative;
}

.modal-content-review {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 90%;
    position: relative;
}
</style>