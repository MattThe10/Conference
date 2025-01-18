<template>
    <div class="conference-item">
        <h2>{{ conference.name }}</h2>
        <p>{{ conference.conference_date_sk }} - {{ conference.submission_deadline_sk }}</p>
        <button @click="$emit('openForm', conference)" class="conference-create-btn btn">+</button>
        <!-- :class="{ disabled: isDisabled(conference) }" -->
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
        isDisabled(conference) {
            console.log(conference);
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