<template>
    <div class="modal-backdrop" v-if="review">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Uprav recenziu
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
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
                        <label for="pros">
                            Prínoy
                        </label>
                        <textarea type="text" id="pros" v-model="pros" required />
                    </div>

                    <div class="textarea-group">
                        <label for="cons">
                            Nedostatky
                        </label>
                        <textarea type="text" id="cons" v-model="cons" required />
                    </div>

                    <div class="textarea-group">
                        <label for="comment">
                            Komentár
                        </label>
                        <textarea type="text" id="comment" v-model="comment" required />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn-submit">
                        Potvrď
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                review_features: [],
                features: {},
                pros: null,
                cons: null,
                comment: null,
            }
        },
        props: {
            review: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            async getData() {
                const review_response = await axios.get(`api/reviews/${ this.review.id }`);
                const review = review_response.data;
                this.review_features = review.review_features;

                for (const feature of this.review_features) {
                    if (feature.rating_type === 1) {
                        this.features[feature.id] = feature.pivot.rating;
                    } else if (feature.rating_type === 0) {
                        this.features[feature.id] = feature.pivot.status; 
                    }
                }
            },
            submit() {
                axios.put(`/api/reviews/${ this.review.id }`,{
                    comment: this.comment,
                    con: this.cons,
                    pro: this.pros,
                    features: this.features,
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri aktualizácii recenzie: ", error);
                    alert("Nepodarilo sa aktualizovať recenziu.");
                });
            },
        },
        watch: {
            review: {
                immediate: true,
                handler (newReview) {
                    if (newReview) {
                        this.pros = this.review.pro,
                        this.cons = this.review.con,
                        this.comment = this.review.comment,

                        this.getData();
                    }
                },
            },
        },    
    };
</script>

<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        padding: 16px;
        border-radius: 12px;
        width: 30vw;
    }

    @media only screen and (max-width: 1200px) {
        .modal {
            width: 50vw;
        }
    }

    @media only screen and (max-width: 720px) {
        .modal {
            width: 80vw;
        }
    }

    @media only screen and (max-width: 440px) {
        .modal {
            width: 100vw;
            height: 100vh;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        font-size: 2rem;
    }

    .modal-header .modal-title {
        display: flex;
        align-items: center;
    }

    .modal-header .btn-close {
        width: 64px;
        height: 64px;
        font-size: 2rem;
        background-color: transparent;
        border: none;
    }

    .modal-header .btn-close::before {
        content: "\2715";
        font-weight: 700;
    }

    .modal-body {
        padding: 16px 0;
    }

    .modal-footer .btn-submit {
        width: 100%;
        height: 64px;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
        font-size: 1.2rem;
    }

    .input-group,
    .select-group {
        display: flex;
        flex-direction: column;
        text-align: left;
        gap: 8px;
        margin-bottom: 16px;
    }

    .input-group label,
    .select-group label {
        font-size: 1.2rem;
    }

    .input-group input,
    .select-group select {
        height: 40px;
        border-radius: 12px;
        font-size: 1.2rem;
        padding: 8px;
    }
</style>
