<template>
    <div class="review-form">
        <h3>Recenzia</h3>
        <div class="scrollable">
            <div class="review-group" v-for="review_feature in review_features" :key="review_feature.id">
                <div class="question">
                    {{ review_feature.question }}
                </div>
                <div class="answer">
                    <span v-if="review_feature.type == 1">
                        <span v-if="review_feature.rating == 1">FX</span>
                        <span v-if="review_feature.rating == 2">E</span>
                        <span v-if="review_feature.rating == 3">D</span>
                        <span v-if="review_feature.rating == 4">C</span>
                        <span v-if="review_feature.rating == 5">B</span>
                        <span v-if="review_feature.rating == 6">A</span>
                    </span>
                    <span v-if="review_feature.type == 0">
                        <span v-if="review_feature.status == 1">Áno</span>
                        <span v-if="review_feature.status == 0">Nie</span>
                    </span>
                </div>
            </div>

            <div class="review-group">
                <div class="question">
                    Prínosy
                </div>
                <div class="answer">
                    {{ pros }}
                </div>
            </div>

            <div class="review-group">
                <div class="question">
                    Nedostatky
                </div>
                <div class="answer">
                    {{ cons }}
                </div>
            </div>

            <div class="review-group">
                <div class="question">
                    Koment
                </div>
                <div class="answer">
                    {{ comment }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            // review_features: [],
            // features: {},
            // // reviewer: '',
            // reviews: [],
            // review_id: null,
            review: null,
            review_features: [],
            pros: '',
            cons: '',
            comment: '',
        };
    },
    methods: {
        async getReview() {
            const reviewId = this.$route.params.reviewId;
            
            const review_response = await axios.get(`/api/reviews/${reviewId}`);
            this.review = review_response.data;

            // this.review.review_features.forEach(review_feature => {

            // });

            this.review_features = this.review.review_features
                .map(element => ({
                    id: element['id'],
                    question: element['content'],
                    type: element['rating_type'],
                    rating: element['pivot']['rating'],
                    status: element['pivot']['status']
                }));

            this.review_features = this.review_features.sort((a, b) => {
                return b.type - a.type;
            });

            this.pros = this.review.pro;
            this.cons = this.review.con;
            this.comment = this.review.comment;
        }
    },
    watch: {
        '$route.params.reviewId': function() {
            this.getReview();
        }
    },
};
</script>

<style scoped>
.btn {
    padding: 10px;
    font-size: 1.2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: none;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    width: 100%;
}

form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 5px;
}


/* Pridané */
.scrollable {
    width: 100%;
    height: 70vh;
    padding: 8px;
    overflow-y: auto;
    margin-bottom: 32px;
}

.review-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 8px;
}

.review-group .question {
    flex: 2;
    text-align: left;
}

.review-group .answer {
    flex: 1;
    text-align: right;
}

.review-group:hover {
    background-color: #ccc;
}

.input-group .question {
    text-align: left;
    flex: 2;
}

.input-group .radios {
    flex: 1;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 2vw;
}

.review-group textarea {
    width: 30vw;
    font-size: 1.5rem;
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