<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <p>Dátum {{ conference.date }}</p>
        <button @click="$emit('openForm', conference)" class="conference-create-btn">Vytvoriť príspevok</button>
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
    padding: 4px;
    font-size: 1rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
    border-radius: 10px;
}
</style>