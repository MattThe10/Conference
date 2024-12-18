<template>
    <div>
        <NavBar></NavBar>
        <div class="article-form">
            <div style="margin-bottom: 16px;">
                <h2>
                    Môj príspevok
                </h2>
            </div>
            <form @submit.prevent="updateArticle">
                <div class="input-group">
                    <label for="title">
                        Názov
                    </label>
                    <input type="text" id="title" v-model="title">
                </div>
                <div class="input-group">
                    <label for="file-pdf">
                        PDF
                    </label>
                    <input type="file" id="file-pdf" @change="onFileChange($event, 'file_pdf');" accept=".pdf">
                </div>
                <div class="input-group">
                    <label for="file-word">
                        Word
                    </label>
                    <input type="file" id="file-word" @change="onFileChange($event, 'file_word');" accept=".doc, .docx">
                </div>
                <div>
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
            article_id: null,
            user_ids: [],
        };
    },
    methods: {
        onFileChange(event, file_type) {
            const file = event.target.files[0];
            if (file) {
                this[file_type] = file;
            }
        },
        async updateArticle() {
            try {
                const user_response = await axios.get("/api/current_user");
                this.user = user_response.data;

                this.user_ids.push(this.user.id);

                const form_data = new FormData();
                form_data.append('title', this.title);
                form_data.append('user_ids[]', this.user_ids);
                form_data.append("file_pdf", this.file_pdf);
                form_data.append("file_word", this.file_word);

                await axios.post(`/api/articles/${this.article_id}`, form_data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.$router.push(`/articles/${this.article_id}`);
            } catch (error) {
                console.log("Article update error: ", error);

                if (error.response) {
                if (error.response.data.errors) {
                    this.errorMessages = Object.values(
                    error.response.data.errors
                    ).flat();
                } else {
                    this.errorMessages = error.response.data.message || "Uknown error";
                }
                }
            }
        }
    },
    mounted() {
       this.article_id = this.$route.params.article_id;
    },
};
</script>

<style scoped>
    .article-form {
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 0 auto;
        width: 80vw;
        height: 80vh;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        align-items: center;
        justify-content: center;
    }

    .input-group,
    .select-group,
    .article-title {
        margin-bottom: 16px;
    }

    .input-group label,
    .select-group label {
        align-content: center;
        text-align: left;
    }

    .input-group input,
    .select-group select {
        height: 4vh;
        align-content: center;
        margin-left: 1vw;
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
