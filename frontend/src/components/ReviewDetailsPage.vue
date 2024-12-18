<template>
    <div>
        <NavBar></NavBar>
        <div class="review-details">
            <div class="review-group" v-for="review_feature in review.review_features" :key="review_feature.id">
                <div class="left">
                    {{ review_feature.content }}
                </div>
                <div class="right">
                    {{ review_feature.pivot.rating }} {{ review_feature.pivot.status }}
                </div>
            </div>
            <div class="review-group">
                <div class="left">
                    Prínosy
                </div>
                <div class="right">
                    {{ review.pro }}
                </div>
            </div>
            <div class="review-group">
                <div class="left">
                    Nedostatky
                </div>
                <div class="right">
                    {{ review.con }}
                </div>
            </div>
            <div class="review-group">
                <div class="left">
                    Komentár
                </div>
                <div class="right">
                    {{ review.comment }}
                </div>
            </div>
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
            review_id: null,
            article: [],
            review: [],
        };
    },
    methods: {
        async getData() {
            const article_response = await axios.get(`/api/articles/${this.article_id}`);
            this.article = article_response.data;

            const review_response = await axios.get(`/api/reviews/${this.review_id}`);
            this.review = review_response.data;
        },
    },
    mounted() {
        this.article_id = this.$route.params.article_id;
        this.review_id = this.$route.params.review_id;

        this.getData();
    },
};
</script>

<style scoped>
.review-details {
    display: flex;
    flex-direction: column;
    position: relative;
    margin: 0 auto;
    height: 90vh;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    align-items: center;
    justify-content: center;
}

.review-group {
    width: 100%;
    display: flex;
    justify-content: space-between;
}

.review-group .left {
    flex: 2;
}

.review-group .right {
    flex: 1;
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
