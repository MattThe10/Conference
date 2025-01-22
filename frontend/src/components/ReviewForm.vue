<template>
    <div class="review-form">
        <h3>{{ article.title }}</h3>
        <form @submit.prevent="submitReview">

            <div class="scrollable">
                <!-- <label for="reviewer">Reviewer</label>
                <input v-model="reviewer" id="reviewer" required /> -->
                
                <div class="input-group" v-for="review_feature in review_features" :key="review_feature.id">
                    <div class="content" v-if="review_feature.is_active == 1">
                        <div class="question">
                            {{ review_feature.content }}
                        </div>
                        <div class="radios" v-if="review_feature.rating_type == 1">
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="6" checked>
                                A
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="5">
                                B
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="4">
                                C
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="3">
                                D
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="2">
                                E
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="1">
                                FX
                            </label>
                        </div>
                        <div class="radios" v-if="review_feature.rating_type == 0">
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="1" checked>
                                Áno
                            </label>
                            <label class="radio-label">
                                <input type="radio" class="radio" :name="'review_feature_' + review_feature.id" v-model="features[review_feature.id]" value="0">
                                Nie
                            </label>
                        </div>
                    </div>
                </div>

                <div class="textarea-group">
                    <label for="pros">
                        Prínosy
                    </label>
                    <textarea id="pros" cols="30" rows="10" v-model="pros"></textarea>
                </div>

                <div class="textarea-group">
                    <label for="cons">
                        Nedostatky
                    </label>
                    <textarea id="cons" cols="30" rows="10" v-model="cons"></textarea>
                </div>

                <div class="textarea-group">
                    <label for="review">
                        Koment
                    </label>
                    <textarea id="review" cols="30" rows="10" v-model="review"></textarea>
                </div>
            </div>

            <div class="btn-wrapper">
                <button type="submit" class="btn">Uložiť</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        article: Object,
    },
    data() {
        return {
            review_features: [],
            features: {},
            // reviewer: '',
            reviews: [],
            review_id: null,
            pros: '',
            cons: '',
            review: '',
        };
    },
    methods: {
        async getReviewFeatures() {
            const review_features_response = await axios.get(`/api/review_features`);
            this.review_features = review_features_response.data.sort((a, b) => {
                return b.rating_type - a.rating_type;
            });

            this.review_features.forEach(feature => {
                if (feature.rating_type === 1) {
                    this.features[feature.id] = 6;
                } else if (feature.rating_type === 0) {
                    this.features[feature.id] = 1; 
                }
            });
        },
        submitReview() {
            console.log(`Review for ${this.article.title}:`, {
                reviewer: this.reviewer,
                review: this.review,
            });

            this.updateReview();

            this.$emit('close');
        },
        async updateReview() {
            try {
                await axios.put(`/api/reviews/${this.review_id}`,{
                    comment: this.review,
                    con: this.cons,
                    pro: this.pros,
                    features: this.features,
                });

                console.log('Successfull store');
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
        },
        async getReview() {
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;

            for (const review of this.user.reviews) {
                if (review.articles_id == this.article.id) {
                    this.review_id = review.id;

                    const review_response = await axios.get(`/api/reviews/${ this.review_id }`);

                    this.review = review_response.data.comment;
                    this.pros = review_response.data.pro;
                    this.cons = review_response.data.con;

                    for (const feature of review_response.data.review_features) {
                        if (feature.rating_type === 1) {
                            this.features[feature.id] = feature.pivot.rating;
                        } else if (feature.rating_type === 0) {
                            this.features[feature.id] = feature.pivot.status; 
                        }
                    }

                    return;
                }
            }
        },
    },
    mounted() {
        this.getReview();
        this.getReviewFeatures();
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

.review-form .input-group .content,
.review-form .textarea-group {
    display: flex;
    gap: 8px;
    justify-content: space-between;
    padding: 16px 8px;
}

.review-form .textarea-group {
    flex-direction: row;
}

.review-form .input-group:hover,
.review-form .textarea-group:hover {
    background-color: #ccc;
}

.review-form .input-group .question {
    text-align: left;
    flex: 2;
}

.review-form .input-group .radios {
    flex: 1;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 2vw;
}

.review-form .textarea-group textarea {
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