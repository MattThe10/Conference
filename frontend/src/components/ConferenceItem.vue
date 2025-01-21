<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <!-- <p>Dátum konferencie {{ conference.conference_date_sk }}</p> -->
        <p>Deadline {{ conference.submission_deadline_sk }}</p>
        <button @click="$emit('openDetails', conference)" class="conference-create-btn btn">+</button>
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
.conference-item {
    width: 100%;
}

.conference-create-btn {
    font-size: 1.5rem;
    width: 2.5rem;
    height: 2.5rem;
}

.conference-create-btn.disabled {
    opacity: .5;
    pointer-events: none;
}
</style>