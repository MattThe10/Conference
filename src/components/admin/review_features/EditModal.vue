<template>
    <div class="modal-backdrop" v-if="review_feature">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Uprav Otázku recenzie
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="textarea-group">
                        <label for="content">
                            Obsah
                        </label>
                        <textarea id="content" cols="30" rows="3" v-model="content" required />
                    </div>

                    <div class="select-group">
                        <label for="rating_type">
                            Typ hodnotenia
                        </label>
                        <select id="rating_type" v-model="ratingType" required>
                            <option value="1">
                                A ~ FX
                            </option>
                            <option value="0">
                                Áno / Nie
                            </option>
                        </select>
                    </div>

                    <div class="select-group">
                        <label for="is_active">
                            Aktívna
                        </label>
                        <select id="is_active" v-model="isActive" required>
                            <option value="1">
                                Áno
                            </option>
                            <option value="0">
                                Nie
                            </option>
                        </select>
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
            content: null,
            ratingType: null,
            isActive: null,
        }
    },
    props: {
        review_feature: {
            type: Object,
            required: true,
        },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        submit() {
            axios.put(`/api/review_features/${this.review_feature.id}`, {
                content: this.content,
                rating_type: this.ratingType,
                is_active: this.isActive,
            })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri aktualizácii otázky recenzie: ", error);
                    alert("Nepodarilo sa aktualizovať otázku recenzie.");
                });
        },
    },
    watch: {
        review_feature: {
            immediate: true,
            handler(newReviewFeature) {
                if (newReviewFeature) {
                    this.content = this.review_feature.content;
                    this.ratingType = this.review_feature.rating_type;
                    this.isActive = this.review_feature.is_active;
                }
            }
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
.select-group,
.textarea-group {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 8px;
    margin-bottom: 16px;
}

.input-group label,
.select-group label,
.textarea-group label {
    font-size: 1.2rem;
}

.input-group input,
.select-group select {
    height: 40px;
    border-radius: 12px;
    font-size: 1.2rem;
    padding: 8px;
}

.textarea-group textarea {
    border-radius: 12px;
    font-size: 1.2rem;
    padding: 8px;
}

.btn-close {
    position: relative;
}
</style>
