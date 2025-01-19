<template>
    <div class="modal-backdrop" v-if="review">
        <div class="modal">

            <div class="modal-header">
                <div class="modal-title">
                    Detaily
                </div>
                <button type="button" class="btn-close" @click="close" />
            </div>

            <div class="modal-body">

                <div class="data-group" v-for="review_feature in review_features" :key="review_feature.id">
                    <div class="type">
                        {{ review_feature.content }}
                    </div>
                    <div class="value">
                        <span v-if="review_feature.rating_type == 1">
                            <span v-if="review_feature.pivot.rating == 1">FX</span>
                            <span v-if="review_feature.pivot.rating == 2">E</span>
                            <span v-if="review_feature.pivot.rating == 3">D</span>
                            <span v-if="review_feature.pivot.rating == 4">C</span>
                            <span v-if="review_feature.pivot.rating == 5">B</span>
                            <span v-if="review_feature.pivot.rating == 6">A</span>
                        </span>
                        <span v-if="review_feature.rating_type == 0">
                            <span v-if="review_feature.pivot.status == 1">Áno</span>
                            <span v-if="review_feature.pivot.status == 0">Nie</span>
                        </span>
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Prínosy
                    </div>
                    <div class="value">
                        {{ review.pro ?? 'Prázdne' }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Nedostatky
                    </div>
                    <div class="value">
                        {{ review.con ?? 'Prázdne' }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Koment
                    </div>
                    <div class="value">
                        {{ review.comment ?? 'Prázdne' }}
                    </div>
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
                review_features: [],
            };
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
            },
        },
        watch: {
            review: {
                immediate: true,
                handler (newReview) {
                    if (newReview) {
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
        max-height: 80vh;
        overflow-y: auto;
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

    .data-group {
        display: flex;
        flex-direction: column;
        text-align: left;
        gap: 8px;
        margin-bottom: 16px;
    }

    .data-group .type {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .data-group .value {
        height: 40px;
        font-size: 1.2rem;
        padding: 8px;
    }
</style>
