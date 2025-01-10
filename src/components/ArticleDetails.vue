<template>
    <div>
        <h3>{{ article.title }}</h3>
        <p>{{ article.author }}</p>
        <p>{{ article.date }}</p>
        <!-- Priestor pre dorobenie file systemu -->
        <p>{{ article.file }}</p>

        <button @click="$emit('openReviewForm')">Pridať komentár</button>
    </div>
</template>

<script>
export default {
    props: {
        article: Object,
    },
};
</script>