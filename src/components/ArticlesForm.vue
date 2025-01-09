<template>
    <div class="modal">
        <h3>Create Article for {{ conference.name }}</h3>
        <form @submit.prevent="submitArticle">
            <label for="title">Názov</label>
            <input type="text" v-model="title" id="title" required />

            <label for="author">Autor</label>
            <input type="text" id="author" v-model="author" required />

            <label for="date">Dátum</label>
            <input type="date" id="date" v-model="date" required>

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_pdf');" accept=".pdf">

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_word');" accept=".doc, .docx">

            <button type="submit" btn-submit>Vložiť</button>
            <button type="button" @click="$emit('close')" id="btn-submit">Zavriet</button>
        </form>
    </div>
    <!-- <div  class="modal-overlay" @click="closeOnOverlayClick">
        <div class="modal-content" @click.stop>
            <form action="" id="articles-form" @submit.prevent="updateArticle">
                <label for="title">Názov</label>
                <input type="text" id="title" v-model="title">
                <label for="author">Autor</label>
                <input type="text" id="author"> -->
    <!-- <label for="date">Dátum</label>
                <input type="date" id="date"> -->
    <!-- <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                    @change="onFileChange($event, 'file_pdf');" accept=".pdf">
                <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                    @change="onFileChange($event, 'file_word');" accept=".doc, .docx">
                <button id="btn-submit">Vložiť</button>
            </form>
        </div>
    </div>  -->
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            user: [],
            articles: [],
            title: '',
            author: '',
            user_ids: [],
            file_pdf: null,
            file_word: null,
        }
    },
    name: "ArticlesForm",
    props: {
        conference: Object,
    },
    methods: {
        //Tu je submit pre form -------PREROBIT PRE BACKEND--------
        submitArticle() {
            console.log(`Article for ${this.conference.name}:`, {
                title: this.title,
                author: this.author
            });
            this.$emit('close');
        },
        // Handle file input change and store the selected file
        onFileChange(event, file_type) {
            const file = event.target.files[0];
            if (file) {
                this[file_type] = file;
            }
        },
        // Update article
        async updateArticle() {
            try {
                // Get current user data
                const user_response = await axios.get("/api/current_user");
                this.user = user_response.data;

                // Get articles data
                const articles_response = await axios.get("/api/articles");
                const articles = articles_response.data;

                // Get articles of current user
                articles.forEach(article => {
                    article['users'].forEach(user => {
                        if (user['id'] == this.user['id']) this.articles.push(article);
                    });
                });

                this.user_ids.push(this.user.id);

                // Put values to formData
                const form_data = new FormData();
                form_data.append('title', this.title);
                form_data.append('user_ids[]', this.user_ids);
                form_data.append("file_pdf", this.file_pdf);
                form_data.append("file_word", this.file_word);

                // Request to update article
                await axios.post(`/api/articles/${this.articles[this.articles.length - 1].id}`, form_data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                // Reload page after successful update
                window.location.reload();
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
};
</script>

<style scoped>
#articles-form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 0.2rem;
    font-size: 1.5rem;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    position: relative;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

#btn-submit {
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
}
</style>