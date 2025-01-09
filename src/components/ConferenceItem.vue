<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <p>Date: {{ conference.date }}</p>
        <button @click="$emit('openForm', conference)">Create Article</button>
    </div>
</template>

<script>
export default {
    props: {
        conference: Object
    }
}
</script>