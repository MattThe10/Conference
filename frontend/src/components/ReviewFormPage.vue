<template>
    <div>
        <NavBar></NavBar>
        <div class="review-form">
            <div style="margin-bottom: 16px;">
                <h1>
                    Recenzia
                </h1>
            </div>
            <form @submit.prevent="updateReview">
                <div class="scrollable">
                    <div class="input-group" v-for="review_feature in review_features" :key="review_feature.id">
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

                    <div class="textarea-group">
                        <label for="pro">
                            Prínosy
                        </label>
                        <textarea id="pro" cols="30" rows="10" v-model="pro"></textarea>
                    </div>

                    <div class="textarea-group">
                        <label for="con">
                            Nedostatky
                        </label>
                        <textarea id="con" cols="30" rows="10" v-model="con"></textarea>
                    </div>

                    <div class="textarea-group">
                        <label for="comment">
                            Koment
                        </label>
                        <textarea id="comment" cols="30" rows="10" v-model="comment"></textarea>
                    </div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="default-button">Potvrdiť</button>
                </div>
            </form>
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
            review_features: [],
            article_id: null,
            review_id: null,
            comment: '',
            con: '',
            pro: '',
            features: {},
        };
    },
    methods: {
        async getData() {
            const review_features_response = await axios.get(`/api/review_features`);
            this.review_features = review_features_response.data;

            this.review_features.forEach(feature => {
            if (feature.rating_type === 1) {
                this.features[feature.id] = 6;
            } else if (feature.rating_type === 0) {
                this.features[feature.id] = 1; 
            }
        });
        },
        async updateReview() {
            try {
                await axios.put(`/api/reviews/${this.review_id}`,{
                    comment: this.comment,
                    con: this.con,
                    pro: this.pro,
                    features: this.features,
                });

                console.log('Successfull store');

                this.$router.push(`/articles/${this.article_id}`);
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
        this.article_id = this.$route.params.articles_for_review_id;
        this.review_id = this.$route.params.review_id;
        this.getData();
    },
};
</script>

<style scoped>
    .review-form {
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 0 auto;
        height: 90vh;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        align-items: center;
        justify-content: center;
    }

    .review-form form {
        display: flex;
        flex-direction: column;
    }

    .scrollable {
        width: 100%;
        height: 70vh;
        overflow-y: auto;
        margin-bottom: 32px;
    }

    .input-group,
    .textarea-group {
        margin-bottom: 32px;
        font-size: 2rem;
        display: flex;
        justify-content: space-between;
    }

    .input-group:hover,
    .textarea-group:hover {
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

    .textarea-group textarea {
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
