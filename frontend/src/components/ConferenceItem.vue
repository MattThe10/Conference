<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <p>Dátum konferencie {{ conference.conference_date_sk }}</p>
        <p>Deadline {{ conference.submission_deadline_sk }}</p>
        <!-- <p>{{ conference.place }}</p>
        <p>{{ conference.street }}</p>
        <p>{{ conference.city }}</p>
        <p>{{ conference.postal_code }}</p> -->
        <button @click="$emit('openForm', conference)" class="conference-create-btn" :class="{ disabled: isDisabled(conference)}">+</button>
        <!-- <button @click="$emit('openForm', conference)" class="conference-create-btn">+</button> -->
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            articles: [],
        };
    },
    props: {
        conference: Object
    },
    methods: {
        isDisabled(conference) {console.log(conference);
            const deadline_date = new Date(conference['submission_deadline']);
            const current_date = new Date();
            
            if (current_date > deadline_date) return true;

            const article_exists = this.articles.some(element => 
                element['conferences_id'] == conference['id']
            );
  
            if (article_exists) return true;

            return false;
        },
        async getArticles() {
            try {
                console.log("Loading article data...");

                const current_user_response = await axios.get("/api/current_user");
                const current_user = current_user_response.data;

                this.articles = current_user.articles;
            } catch (error) {
                console.log("Error loading user data: ", error);
            }
        },
    },
    mounted() {
        this.getArticles();
    },
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

.conference-create-btn.disabled {
    opacity: .5;
    pointer-events: none;
}
</style>