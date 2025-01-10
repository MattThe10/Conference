<template>
    <div>
        <h3>Komentár pre {{ article.title }}</h3>
        <form @submit.prevent="submitReview">
            <label for="reviewer">Reviewer</label>
            <input v-model="reviewer" id="reviewer" required />

            <label for="review">Comment</label>
            <textarea v-model="review" id="review" required></textarea>

            <div class="form-buttons">
                <button type="submit">Odoslať</button>
                <button type="button" @click="$emit('close')">Zavrieť</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        article: Object,
    },
    data() {
        return {
            reviewer: '',
            review: '',
        };
    },
    methods: {
        submitReview() {
            console.log(`Review for ${this.article.title}:`, {
                reviewer: this.reviewer,
                review: this.review,
            });
            this.$emit('close');
        },
    },
};
</script>

<style scoped>
.form-buttons {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}
</style>