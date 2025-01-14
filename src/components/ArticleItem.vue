<template>
    <div class="article-item" @click="$emit('openDetails', article, source)">
        <h3>
            {{ article.title }}
            <span v-if="source == 'articles'">
                - {{ article.status }}
            </span>
        </h3>
    </div>
</template>

<script>
export default {
    props: {
        article: Object,
        source: String,
    }
}
</script>

<style scoped>
.article-item {
    cursor: pointer;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.article-item:hover {
    background: #52b69a;
}
</style>