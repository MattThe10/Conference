<template>
    <div>
        <h3>{{ article.title }}</h3>
        <form @submit.prevent="submitReview">
            <label for="reviewer">Reviewer</label>
            <input v-model="reviewer" id="reviewer" required />

            <label for="review">Comment</label>
            <textarea v-model="review" id="review" required></textarea>

            <div class="btn-wrapper">
                <button type="submit" class="btn">Odoslať</button>
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

form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 5px;
}
</style>