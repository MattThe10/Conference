<template>
    <div class="modal-backdrop" v-if="article">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Uprav príspevok
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <label for="title">
                            Názov
                        </label>
                        <input type="text" id="title" v-model="title" required>
                    </div>

                    <div class="textarea-group">
                        <label for="abstract">
                            Abstrakt
                        </label>
                        <textarea type="text" id="abstract" v-model="abstract" required />
                    </div>

                    <div class="input-group">
                        <label for="keywords">
                            Kľúčové slová
                        </label>
                        <input type="text" id="keywords" v-model="keywords" required>
                    </div>

                    <div class="select-group">
                        <label for="conference">
                            Konferencia
                        </label>
                        <select id="conference" v-model="conferenceId" required>
                            <option v-for="conference in conferences" :key="conference.id" :value="conference.id">
                                {{ conference.title }}
                            </option>
                        </select>
                    </div>

                    <div class="select-group">
                        <label for="status">
                            Status
                        </label>
                        <select id="status" v-model="articleStatusId" required>
                            <option v-for="article_status in article_statuses" :key="article_status.id" :value="article_status.id">
                                {{ article_status.name }}
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
                title: null,
                abstract:null,
                keywords:null,
                conferenceId: null,
                articleStatusId: null,

                conferences: null,
                article_statuses: null,
            }
        },
        props: {
            article: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            async getData() {
                const conferences_response = await axios.get("/api/conferences");
                this.conferences = conferences_response.data;

                const article_statuses_response = await axios.get("/api/article_statuses");
                this.article_statuses = article_statuses_response.data;
            },
            submit() {
                axios.post(`/api/articles/${this.article.id}`, {
                    title: this.title,
                    abstract: this.abstract,
                    keywords: this.keywords,
                    article_status_id: this.articleStatusId,
                    conference_id: this.conferenceId,
                })
                .then(() => {
                    location.reload();
                })
                .catch((error) => {
                    console.error("Chyba pri aktualizácii príspevku: ", error);
                    alert("Nepodarilo sa aktualizovať príspevok.");
                });
            },
        },
        mounted() {
            this.getData();
        },
        watch: {
            article: {
                immediate: true,
                handler (newArticle) {
                    if (newArticle) {
                        this.title = this.article.title;
                        this.abstract = this.article.abstract;
                        this.keywords = this.article.keywords;
                        this.articleStatusId = this.article.article_statuses_id;
                        this.conferenceId = this.article.conferences_id;
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
