<template>
    <div class="modal-backdrop">
        <div class="modal">

            <form @submit.prevent="submit">
                <div class="modal-header">
                    <div class="modal-title">
                        Priraď príspevok
                    </div>
                    <button type="button" class="btn-close" @click="close" />
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <label for="email">
                            Email používateľa 
                        </label>
                        <input type="email" id="email" v-model="email" required>
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
                email: null,
                articles: [],
            }
        },
        props: {
            selectedArticles: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            async getData() {
                    const articles_response = await axios.get('/api/articles');
                    const articles_data = articles_response.data;
                    this.articles = articles_data.filter(article => {
                        return this.selectedArticles.includes(article.id) && ['accepted', 'accepted_with_conditions'].includes(article.article_status.key);
                    });
            },
            async submit() {
                try {
                    const user_response = await axios.get(`/api/users?search=${this.email}`);
                    const user = user_response.data;
                    
                    if (user.length == 0) {
                        alert('Používateľ s týmto e-mailom neexistuje.');
                        return;
                    }

                    if (this.selectedArticles.length != this.articles.length) {
                        alert('Neplatné príspevky. Prosím vyberajte iba prijaté príspevky.');
                        return;
                    }

                    this.selectedArticles.forEach(selectedArticle => {
                        axios.post("/api/reviews", {
                            user_id: user[0].id,
                            article_id: selectedArticle,
                            article_for_review: true,
                        })
                        .then(() => {
                            alert('Príspevky boli priradené recenzentovi.');
                            location.reload();
                        });
                    });
                } catch (error) {
                    console.error('Chyba pri priradení príspevkov: ', error);
                    alert('Nepodarilo sa priradiť príspevok. Skontrolujte e-mail.');
                }
            },
        },
        watch: {
            selectedArticles: {
                immediate: true,
                handler () {
                    this.getData();
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
