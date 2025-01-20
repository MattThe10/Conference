<template>
    <div>
        <h3>Uprav príspevok</h3>
        <form id="article-form">
            <label for="title">Názov</label>
            <input type="text" v-model="title" id="title" @input="clearError('email')" required />
            <p v-if="errors.title" class="error">{{ errors.title[0] }}</p>

            <label for="email">Pridať autora</label>
            <div>
                <input type="email" v-model="new_author_email" placeholder="Zadajte e-mail" />
                <button type="button" class="btn" @click="addAuthor">Pridať</button>
            </div>
            <ul>
                <li v-for="id in user_ids" :key="id">
                    {{ getUserName(id) }}
                    <button type="button" class="btn" @click="removeAuthor(id)">Odstrániť</button>
                </li>
            </ul>

            <label for="abstract">Abstrakt</label>
            <textarea id="abstract" cols="30" rows="3" v-model="abstract"></textarea>
            <p v-if="errors.abstract" class="error">{{ errors.abstract[0] }}</p>

            <label for="keywords">Kľúčové slová</label>
            <input type="text" id="keywords" v-model="keywords">
            <p v-if="errors.keywords" class="error">{{ errors.keywords[0] }}</p>

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_pdf');" accept=".pdf">

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_word');" accept=".doc, .docx">

            <button type="button" class="btn" :disabled="!isFormValid" @click="submitArticle">Odoslať</button>
            <button type="button" class="btn" @click="saveArticle" id="btn-submit">Uložiť</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        article: Object,
    },
    data() {
        return {
            user: [],
            users: [],
            user_ids: [],
            title: this.article.title,
            abstract: this.article.abstract,
            keywords: this.article.keywords,
            new_author_email: '',
            file_pdf: null,
            file_word: null,
            files_uploaded: false,

            errors: {},
        }
    },
    methods: {
        async addAuthor() {
            try {
                const user_response = await axios.get(`/api/users?search=${this.new_author_email}`);
                const users = user_response.data;
                if (users.length == 0) {
                    alert('Používateľ s týmto e-mailom neexistuje.');
                    return;
                }

                if (!this.user_ids.includes(users[0].id)) {
                    this.user_ids.push(users[0].id);
                    alert(`Používateľ ${users[0].name} bol pridaný.`);
                } else {
                    alert(`Tento používateľ je už pridaný.`);
                }

                this.new_author_email = '';
            } catch (error) {
                console.error('Chyba pri pridávaní autora: ', error);
                alert('Nepodarilo sa pridať používateľa. Skontrolujte e-mail.');
            }
        },
        removeAuthor(user_id) {
            this.user_ids = this.user_ids.filter(id => id !== user_id);
        },
        getUserName(user_id) {
            const user = this.users.find(user => user.id === user_id);
            return user ? user.name + ' ' + user.surname : 'Neznámy užívateľ';
        },
        async getUsers() {
            const user_response = await axios.get('/api/users');
            this.users = user_response.data;
        },
        async setUsers() {
            this.article.authors.forEach(user => {
                this.user_ids.push(user.id)
            });
        },
        async getData() {
            const article_response = await axios.get(`/api/articles/${this.article.id}`);
            const article_data = article_response.data;

            if (article_data.documents.length >= 2) this.files_uploaded = true;
        },
        submitArticle() {
            this.updateArticle('submit');

            console.log(`Article for ${this.title}:`, {
                title: this.title,
                // author: this.author
            });
        },
        saveArticle() {
            this.updateArticle('save');

            console.log(`Article for ${this.title}:`, {
                title: this.title,
                // author: this.author
            });
        },
        // Handle file input change and store the selected file
        onFileChange(event, file_type) {
            const file = event.target.files[0];
            if (file) {
                this[file_type] = file;
            }
        },
        // Update article
        async updateArticle(type) {
            try {
                // Get current user data
                const user_response = await axios.get("/api/current_user");
                this.user = user_response.data;

                this.articles = this.user.articles;

                if (!this.user_ids.includes(this.user.id)) {
                    this.user_ids.push(this.user.id);
                }

                // Put values to formData
                const form_data = new FormData();
                form_data.append('title', this.title);
                form_data.append('abstract', this.abstract);
                form_data.append('keywords', this.keywords);

                this.user_ids.forEach(author_id => {
                    form_data.append('user_ids[]', author_id)
                });

                if (this.file_pdf) {
                    form_data.append("file_pdf", this.file_pdf);
                }

                if (this.file_word) {
                    form_data.append("file_word", this.file_word);
                }

                const article_response = await axios.get(`/api/articles/${this.article.id}`);
                const article_data = article_response.data;

                form_data.append("conference_id", article_data.conference.id);

                const article_statuses_response = await axios.get("/api/article_statuses");
                const article_statuses = article_statuses_response.data;

                var status = null;

                if (type == "save") {
                    status = article_statuses.find(article_status => article_status.key == 'draft');
                }
                else if (type == "submit") {
                    status = article_statuses.find(article_status => article_status.key == 'submitted');
                }
                form_data.append("article_status_id", status.id);

                // Request to update article
                await axios.post(`/api/articles/${this.articles[this.articles.length - 1].id}`, form_data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.$emit('close');

                // Reload page after successful update
                window.location.reload();
            } catch (error) {
                console.log("Article update error: ", error);

                this.errors = error.response.data.errors;

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
        },
        clearError(field) {
            if (this.errors[field]) {
                delete this.errors[field];
            }
        },
    },
    mounted() {
        this.getUsers();
        this.setUsers();
        this.getData();
    },
    computed: {
        isFormValid() {
            return (
                this.title !== '' &&
                this.abstract !== '' &&
                this.keywords !== '' &&
                (this.files_uploaded || this.file_pdf !== null) &&
                (this.files_uploaded || this.file_word !== null) &&
                this.user_ids.length >= 1
            );
        }
    },
};
</script>

<style scoped>
.btn {
    padding: 10px;
    font-size: 1.2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: none;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    width: 100%;
}

.btn:disabled {
    opacity: .5;
    pointer-events: none;
}

form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 5px;
}


/* Pridané */
.scrollable {
    width: 100%;
    height: 70vh;
    padding: 8px;
    overflow-y: auto;
    margin-bottom: 32px;
}

.input-group,
.textarea-group {
    display: flex;
    justify-content: space-between;
    padding: 16px 8px;
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

.error {
    color: #dc3545;
}
</style>