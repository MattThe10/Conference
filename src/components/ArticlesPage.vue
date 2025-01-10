<template>
    <NavBar></NavBar>
    <h2 class="articles-header">Moje príspevky</h2>
    <div id="articles-wrapper">
        <div id="articles-display">

        </div>
    </div>
    <div id="articles-button"> </div>
</template>
<script>
import axios from "axios";
import NavBar from '@/components/NavBar.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'


export default {
    components: {
        NavBar,
        ArticlesForm,
    },
    data() {
        return {
            user: [],
            articles: [],
        }
    },
    methods: {
        async getData() {
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;

            const articles_response = await axios.get("/api/articles");
            const articles = articles_response.data;

            articles.forEach(article => {
                article['users'].forEach(user => {
                    if (user['id'] == this.user['id']) this.articles.push(article);
                });
            });
        },
        openForm(conference) {
            this.selectedConference = conference;
        },
        closeForm() {
            this.selectedConference = null;
        },
    },
    mounted() {
        this.getData();
    },
}
</script>

<style scoped>
#articles-wrapper {
    display: flex;
    flex-direction: column;
    width: 40%;
    border: 2px solid black;
    margin: 0 auto;
    height: 100%;
}

.articles-header {
    font-size: 3rem;
}

#articles-display {
    font-size: 1.5rem;
}

.articles-li:first-child {
    margin-top: 1rem;
}

.articles-li {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    height: 4rem;
    margin-bottom: 1rem;
}

.articles-li-div {
    font-size: 1.2rem;
    display: flex;
    flex-direction: row;
    justify-content: center;
    gap: 1rem;
}

#articles-button {}

#btn-insert {
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
    position: relative;
    left: 13.3%;

}
</style>