<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <p>Dátum {{ conference.date }}</p>
        <button @click="$emit('openForm', conference)" class="conference-create-btn">+</button>
    </div>
</template>

<script>
export default {
    props: {
        conference: Object
    }
}
</script>
<style scoped>
.conference-create-btn {
    padding: 10px;
    font-size: 1.2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: none;
    border-radius: 10px;
    width: 2.5rem;
}
</style>