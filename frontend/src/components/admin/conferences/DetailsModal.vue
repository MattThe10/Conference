<template>
    <div class="modal-backdrop" v-if="conference">
        <div class="modal">

            <div class="modal-header">
                <div class="modal-title">
                    Detaily
                </div>
                <button type="button" class="btn-close" @click="close" />
            </div>

            <div class="modal-body">

                <div class="data-group">
                    <div class="type">
                        Názov
                    </div>
                    <div class="value">
                        {{ conference.title }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Abstrakt
                    </div>
                    <div class="value">
                        {{ conference.abstract }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Miesto konania
                    </div>
                    <div class="value">
                        {{ conference.university.name }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Dátum konferencie
                    </div>
                    <div class="value">
                        {{
                            new Date(conference.conference_date).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            })
                        }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Deadline na odovzdania prác
                    </div>
                    <div class="value">
                        {{
                            new Date(conference.submission_deadline).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            })
                        }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Deadline na priradenie publikácie 
                    </div>
                    <div class="value">
                        {{
                            new Date(conference.review_assignment_deadline).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            })
                        }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Deadline na recenzovanie
                    </div>
                    <div class="value">
                        {{
                            new Date(conference.review_submission_deadline).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            })
                        }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Dátum zverejnenia recenzií
                    </div>
                    <div class="value">
                        {{
                            new Date(conference.review_publication_date).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                            })
                        }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Je aktívna?
                    </div>
                    <div class="value">
                        {{ conference.is_active == 1 ? 'Áno' : 'Nie' }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Príspevky
                    </div>
                    <div class="value reviews">
                        <button type="button" :class="{ disabled: isArticlesButtonDisabled() }" @click="showArticleList()">
                            Zobraziť
                        </button>
                    </div>
                    <div class="value reviews">
                        <button type="button" :class="{ disabled: isDownloadButtonDisabled() }" @click="downloadFile()">
                            Stiahnuť
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            conference: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            isArticlesButtonDisabled() {
                if (this.conference.articles.length > 0) {
                    return false;
                } else {
                    return true;
                }
            },
            showArticleList() {
                this.$router.push({ name: 'ConferenceArticlesDataList', params: { id: this.conference.id } });
            },
            isDownloadButtonDisabled() {
                if (this.conference.articles) {
                    const has_documents = this.conference.articles.some(article => 
                        article.documents && article.documents.length >= 2
                    );
                    
                    return !has_documents;
                } else {
                    return true;
                }
            },
            downloadFile() {
                let article_ids = [];
                
                this.conference.articles.forEach(element => {
                    article_ids.push(element.id);
                });

                axios({
                    url: `${process.env.VUE_APP_BACKEND_URL}/api/articles/download`,
                    method: 'POST',
                    responseType: 'blob',
                    data: { article_ids: article_ids }
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'article.zip');
                    document.body.appendChild(link);
                    link.click();
                });
            },
        }
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
