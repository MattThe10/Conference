<template>
    <div class="modal-backdrop" v-if="article">
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
                        {{ article.title }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Autori
                    </div>
                    <div class="value">
                        <ul>
                            <li v-for="user in article.users" :key="user.id">
                                {{ user.name }} {{ user.surname }} (ID: {{ user.id }})
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Dokumenty
                    </div>
                    <div class="value download">
                        <button type="button" @click="downloadFile()">
                            Stiahnuť
                        </button>
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Konferencia
                    </div>
                    <div class="value">
                        Konferencia {{ article.conference.start_year }} / {{ article.conference.end_year }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Stav
                    </div>
                    <div class="value">
                        {{ article.article_status.name }}
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
            article: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            downloadFile() {
                axios({
                    url: `${process.env.VUE_APP_BACKEND_URL}/api/articles/download`,
                    method: 'POST',
                    responseType: 'blob',
                    data: { article_ids: [this.article.id] }
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
        height: 100% !important;
        font-size: 1.2rem;
        padding: 8px;
    }

    .data-group .value ul {
        list-style: none;
    }

    .data-group .value ul li:not(:last-child) {
        margin-bottom: 8px;
    }

    .value.download {
        height: 64px !important;
    }

    .value.download button {
        width: 100%;
        height: 100%;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
        font-size: 1.2rem;
    }
</style>
