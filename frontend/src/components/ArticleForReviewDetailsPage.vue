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
            <router-link :to="{ path: `/articles_for_review/${article?.id}/reviews/${my_review_id}/review_form/` }">Recenzovať</router-link>
        </div>
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
            my_review_id: null,
            user: [],
        };
    },
    methods: {
        async getData() {
            const article_response = await axios.get(`/api/articles/${this.article_id}`);
            this.article = article_response.data;

            const user_response = await axios.get(`/api/current_user`);
            this.user = user_response.data;

            this.article.reviews.forEach(review => {
                if (review.comment != null) {
                    this.reviews.push(review);
                }
                
                if (review.users_id == this.user.id) {
                    this.my_review_id = review.id;
                    console.log(this.my_review_id);
                }
            });
        },
    },
    mounted() {
        this.article_id = this.$route.params.articles_for_review_id;

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
</style>
