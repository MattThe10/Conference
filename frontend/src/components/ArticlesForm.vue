<template>
    <div class="modal">
        <h2>{{ conference.name }}</h2>
        <form id="article-form">
            <label for="title">Názov</label>
            <input type="text" v-model="title" id="title" @input="clearError('title')" required />
            <p v-if="errors.title" class="error">{{ errors.title[0] }}</p>

            <label for="email">Autor</label>
            <div>
                <input type="email" v-model="new_author_email" placeholder="Zadajte e-mail" required />
                <button type="button" class="btn" @click="addAuthor">Pridať</button>
            </div>
            <ul>
                <li v-for="id in user_ids" :key="id">
                    {{ getUserName(id) }}
                    <button type="button" @click="removeAuthor(id)">Odstrániť</button>
                </li>
            </ul>

            <label for="abstract">Abstrakt</label>
            <textarea id="abstract" cols="30" rows="3" v-model="abstract" @input="clearError('abstract')"></textarea>
            <p v-if="errors.abstract" class="error">{{ errors.abstract[0] }}</p>

            <label for="keywords">Kľúčové slová</label>
            <input type="text" id="keywords" v-model="keywords" @input="clearError('keywords')">
            <p v-if="errors.keywords" class="error">{{ errors.keywords[0] }}</p>

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_pdf');" accept=".pdf" @input="clearError('file_pdf')" required>
            <p v-if="errors.file_pdf" class="error">{{ errors.file_pdf[0] }}</p>

            <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;"
                @change="onFileChange($event, 'file_word');" accept=".doc, .docx" @input="clearError('file_word')" required>
            <p v-if="errors.file_word" class="error">{{ errors.file_word[0] }}</p>

            <button type="button" class="btn btn-submit" :disabled="!isFormValid" @click="submitArticle">Odoslať</button>
            <button type="button" class="btn btn-submit" :disabled="!isFormValid" @click="saveArticle">Uložiť</button>
        </form>
    </div>

</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            user: [],
            users: [],
            user_ids: [],
            title: '',
            abstract: '',
            keywords: '',
            new_author_email: '',
            file_pdf: null,
            file_word: null,

            errors: {},
        }
    },
    name: "ArticlesForm",
    props: {
        conference: Object,
    },
    methods: {
        // Add author to list
        async addAuthor() {
            try {
                const users_response = await axios.get(`/api/users?search=${this.new_author_email}`);
                const user = users_response.data[0];

                if (user == null) {
                    alert('Používateľ s týmto e-mailom neexistuje.');
                    return;
                }

                if (!['reviewer', 'student'].includes(user.role.key) || user.id == this.user.id) {
                    alert('Toho používateľa nie je možné pridať.');
                    return;   
                }

                if (!this.user_ids.includes(user.id)) {
                    this.user_ids.push(user.id);
                    alert(`Používateľ ${user.name} bol pridaný.`);
                } else {
                    alert(`Tento používateľ je už pridaný.`);
                }

                this.new_author_email = '';
            } catch (error) {
                console.error('Chyba pri pridávaní autora: ', error);
                alert('Nepodarilo sa pridať používateľa. Skontrolujte e-mail.');
            }
        },

        // Remove author from list
        removeAuthor(user_id) {
            this.user_ids = this.user_ids.filter(id => id !== user_id);
        },

        // Get user's name
        getUserName(user_id) {
            const user = this.users.find(user => user.id === user_id);
            return user ? user.name + ' ' + user.surname : 'Neznámy užívateľ';
        },

        async getData() {
            // Get current user
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;

            // Get all users
            const users_response = await axios.get('/api/users');
            this.users = users_response.data;
        },

        //Tu je submit pre form -------PREROBIT PRE BACKEND--------
        async submitArticle() {
            await this.updateArticle('submit');

            console.log(`Article for ${this.conference.name}:`, {
                title: this.title,
                author: this.author
            });
        },

        async saveArticle() {
            await this.updateArticle('save');

            console.log(`Article for ${this.conference.name}:`, {
                title: this.title,
                author: this.author
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

                form_data.append("file_pdf", this.file_pdf);
                form_data.append("file_word", this.file_word);

                form_data.append("conference_id", this.conference.id);

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

                const article_response = await axios.get(`/api/articles/${this.articles[this.articles.length - 1].id}`);
                const article = article_response.data;

                form_data.append("conferences_id", article.conference.id);

                // Request to update article
                await axios.post(`/api/articles/${article.id}`, form_data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                // Reload page after successful update
                // window.location.reload();

                this.$router.push({ name: 'ArticlesPage' });
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
        this.getData();
    },
    computed: {
        isFormValid() {
            return (
                this.title !== '' &&
                this.abstract !== '' &&
                this.keywords !== '' &&
                (this.files_uploaded || this.file_pdf !== null) &&
                (this.files_uploaded || this.file_word !== null)
            );
        }
    },
};
</script>

<style scoped>
#article-form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 0.2rem;
    font-size: 1.2rem;
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

.btn-submit {
    padding: 5px;
    font-size: 1.5rem;
}

.btn-submit:disabled {
    opacity: .5;
    pointer-events: none;
}

.error {
    color: #dc3545;
}
</style>