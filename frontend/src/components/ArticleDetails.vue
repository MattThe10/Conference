<template>
    <div class="article-wrapper">
        <h3>{{ article.title }}</h3>
        <p>{{ article.author }}</p>
        <p>{{ article.date }}</p>
        <!-- Priestor pre dorobenie file systemu -->
        <p>{{ article.file }}</p>

        <button @click="$emit('openReviewForm')" class="btn-add">Pridať komentár</button>

        <hr />

        <!-- Reviews Section -->
        <h4>Komentáre</h4>
        <div v-if="article.reviews.length > 0">
            <div v-for="(review, index) in article.reviews" :key="index" class="review">
                <p><strong>{{ review.reviewer }}</strong>: {{ review.content }}</p>
            </div>
        </div>
        <div v-else>
            <p>Zatiaľ žiadne komentáre</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        article: Object,
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
</style>