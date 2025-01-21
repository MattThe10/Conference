<template>
    <div class="article-wrapper">
        <h3>{{ article.title }} - {{ article.status }}</h3>
        <p>{{ article.conference }}</p>

        <h5>Abstrakt</h5>
        <p>{{ article.abstract ?? 'Prázdne' }}</p>

        <h5>Kľúčové slová</h5>
        <p>{{ article.keywords ??'Prázdne' }}</p>

        <h5>Autori</h5>
        <p v-for="(author, index) in article.authors" :key="index">
            {{ author.name }} {{ author.surname }} ({{ author.email }})
        </p>

        <h5>Admin</h5>
        <p v-for="(admin, index) in admins" :key="index">
            <span v-if="admin.role.key == 'admin'">
                {{ admin.name }} {{ admin.surname }} ({{ admin.email }})
            </span>
            <span v-if="admin.role.key == 'super_admin'">
                {{ admin.email }}
            </span>
        </p>

        <button @click="downloadFile()" class="btn-add document-download-btn btn"
            :class="{ disabled: download_disabled }">Stiahnuť</button>

        <button v-if="source == 'articlesForReview'" @click="$emit('openReviewForm')" class="btn-add article-edit-btn btn" :class="{ disabled: review_disabled }">
            Recenzovať
        </button>

        <button v-if="source == 'articles'" @click="$emit('openArticleEditForm', article)"
            class="btn-add article-edit-btn btn" :class="{ disabled: edit_disabled }">Upraviť</button>

        <button v-if="source == 'articles'" @click="deleteArticle()"
            class="btn-delete article-edit-btn btn" :class="{ disabled: delete_disabled }">Odstrániť</button>

        <p v-if="source == 'articles'">Termín na odovzdanie príspevku: {{ article.date }}</p>
        <p v-if="source == 'articlesForReview'">Termín na vyplnenie recenzie: {{ article.date }}</p>

        <hr />

        <!-- Reviews Section -->
        <div v-if="source == 'articles'">
            <!-- <h4>Komentáre</h4> -->
            <h4>Recenzie</h4>
            <div v-if="article.reviews.length > 0 && reviews_published">
                <div v-for="(review, index) in article.reviews" :key="index" class="review">
                    <!-- <p><strong>{{ review.reviewer }}</strong>: {{ review.content }}</p> -->
                    <button @click="$emit('openReviewDetails', article, review)" class="btn-add">Recenzia {{ index + 1
                        }}</button>
                </div>
            </div>
            <div v-else>
                <!-- <p>Zatiaľ žiadne komentáre</p> -->
                <p>Zatiaľ žiadne recenzie</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            article_data: null,
            reviews_published: null,
            edit_disabled: false,
            download_disabled: false,
            delete_disabled: false,
            review_disabled: false,
            users: [],
            admins: [],
            current_user: null,
        };
    },
    props: {
        article: Object,
        source: String,
    },
    methods: {
        async getData() {
            const article_response = await axios.get(`/api/articles/${this.article.id}`);
            this.article_data = article_response.data;

            const current_date = new Date();

            if (this.source == 'articles') {
                const deadline_date = new Date(this.article_data.conference['submission_deadline']);
                this.edit_disabled = current_date > deadline_date ||!['draft', 'returned'].includes(this.article_data.article_status.key);

                this.delete_disabled = !['draft', 'returned'].includes(this.article_data.article_status.key);

                const review_publication_date = new Date(this.article_data.conference['review_publication_date']);
                this.reviews_published = current_date > review_publication_date;
            } else {
                const deadline_date = new Date(this.article_data.conference['review_submission_deadline']);
                this.edit_disabled = current_date > deadline_date;

                this.review_disabled = current_date > deadline_date;
            }

            if (this.article_data.documents.length >= 2) this.download_disabled = false;
            else this.download_disabled = true;

            const current_user_response = await axios.get(`/api/current_user`);
            this.current_user = current_user_response.data;

            const users_response = await axios.get(`/api/users`);
            this.users = users_response.data;

            this.admins = this.users.filter(user => {
                return (user.faculty.universities_id == this.current_user.faculty.universities_id && user.role.key == 'admin');
            });

            if (this.admins.length == 0) {
                this.admins = this.users.filter(user => {
                    return (user.role.key == 'super_admin');
                });
            }
        },
        downloadFile() {
            console.log('Odosielaná požiadavka na URL:', `${process.env.VUE_APP_BACKEND_URL}/api/articles/download`);
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
        deleteArticle() {
            axios.delete(`/api/articles/${this.article.id}`)
            .then(() => {
                location.reload();
            })
            .catch((error) => {
                console.error("Chyba pri odstraňovaní príspevku: ", error);

                if (this.article.reviews.length > 0) {
                    alert("Tento príspevok je už recenzovaný.");
                } else {
                    alert("Nepodarilo sa odstrániť príspevok.");
                }
            });
        },
    },
    mounted() {
        this.getData();
    },
};
</script>

<style scoped>
.article-wrapper {
    display: flex;
    flex-direction: column;
    gap: 5px;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

.btn-add,
.btn-delete {
    padding: 10px;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    width: 10rem;
}



/* Pridané */
.document-download-btn.disabled,
.article-edit-btn.disabled {
    opacity: .5;
    pointer-events: none;
}
</style>