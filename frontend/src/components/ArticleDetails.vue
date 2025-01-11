<template>
    <div class="article-wrapper">
        <h3>{{ article.title }}</h3>
        <p>{{ article.conference }}</p>
        <p v-for="(author, index) in article.authors" :key="index">
            {{ author.name }} {{ author.surname }}
        </p>
        <p>{{ article.date }}</p>
        <p>{{ article.status }}</p>
        <!-- Priestor pre dorobenie file systemu -->
        <!-- <p>{{ article.file }}</p> -->

        <button @click="downloadFile()" class="btn-add document-download-btn" :class="{ disabled: download_disabled}">Stiahnuť</button>

        <button v-if="source == 'articlesForReview'" @click="$emit('openReviewForm')" class="btn-add">
            <!-- Pridať komentár -->
            Recenzovať
        </button>

        <button v-if="source == 'articles'" @click="$emit('openArticleEditForm', article)"  class="btn-add article-edit-btn" :class="{ disabled: edit_disabled}">Upraviť</button>

        <hr />

        <!-- Reviews Section -->
        <div v-if="source == 'articles'">
            <!-- <h4>Komentáre</h4> -->
            <h4>Recenzie</h4>
            <div v-if="article.reviews.length > 0">
                <div v-for="(review, index) in article.reviews" :key="index" class="review">
                    <!-- <p><strong>{{ review.reviewer }}</strong>: {{ review.content }}</p> -->
                    <button @click="$emit('openReviewDetails', article, review)" class="btn-add">Recenzia {{ index + 1 }}</button>
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
            edit_disabled: false,
            download_disabled: false,
        };
    },
    props: {
        article: Object,
        source: String,
    },
    methods: {
        async getData() {
            const article_response = await axios.get(`/api/articles/${ this.article.id }`);
            this.article_data = article_response.data;

            const deadline_date = new Date(this.article_data.conference['submission_deadline']);
            const current_date = new Date();

            this.edit_disabled = current_date > deadline_date || !['draft', 'returned'].includes(this.article_data.article_status.key);

            if (this.article_data.documents.length >= 2) this.download_disabled = false; 
            else this.download_disabled = true; 
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

.btn-add {
    padding: 10px;
    font-size: 1.2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: none;
    border-radius: 10px;
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