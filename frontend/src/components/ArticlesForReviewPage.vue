<template>
    <div>
        <NavBar></NavBar>
        <h2 class="articles-header">Recenzuj</h2>
        <div id="articles-wrapper">
            <div id="articles-display">
                <ul>
                    <!-- <li v-for="dummy in dummies" class="articles-li">
                        <span>{{ dummy.article_name }}</span>
                        <div class="articles-li-div">
                            <span>{{ dummy.article_author }}</span>
                            <span>{{ dummy.article_date }}</span>
                        </div>
                        <hr v-if="dummy.id !== 4">
                    </li> -->
                    <li v-for="review in user.reviews" class="articles-li" :key="review.id" style="height: 100%;">
                        <span>{{ review.article.title }}</span>
                        <div style="font-size: 1.2rem;">
                            <div v-for="user in review.article.users" :key="user.id">
                                {{ user.name.charAt(0) }}.
                                {{ user.surname }} 
                            </div>
                            <div>
                                Konferencia {{ review.article.conference.start_year }} / {{ review.article.conference.end_year }}
                            </div>
                            <div>
                                {{ review.article.article_status.name }}
                            </div>
                            <router-link :to="{ path: `/articles_for_review/${review.article.id}` }">Detaily</router-link>
                        </div>
                        <hr v-if="review.article.id !== 4">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import NavBar from '@/components/NavBar.vue'

export default {
    components: {
        NavBar,
    },
    data() {
        return {
            // dummies: [
            //     {
            //         id: 1,
            //         article_name: 'React JS a jeho využitie v praxi',
            //         article_author: 'Ján Dlhý',
            //         article_date: '11-12-24'
            //     },
            //     {
            //         id: 2,
            //         article_name: 'Vue JS a technológie',
            //         article_author: 'Patrik Šedivý',
            //         article_date: '09-03-22'
            //     },
            //     {
            //         id: 3,
            //         article_name: 'Angular JS a jeho miesto v korporátoch',
            //         article_author: 'Tomáš Tóth',
            //         article_date: '03-03-24'
            //     },
            //     {
            //         id: 4,
            //         article_name: 'Next JS ako moderný framework',
            //         article_author: 'Dávid Dlhoprstý',
            //         article_date: '14-09-23'
            //     },
            // ],
            user: [],
            articles: [],
            showModal: false,
        }
    },
    methods: {
        async getData() {
            const user_response = await axios.get("/api/current_user");
            this.user = user_response.data;console.log(this.user);console.log(this.user);

            const articles_response = await axios.get("/api/articles");
            const articles = articles_response.data;

            articles.forEach(article => {
                article['users'].forEach(user => {
                    if (user['id'] == this.user['id']) this.articles.push(article);
                });
            });
        },
        async createArticle() {
            try {
                // Request to create article
                await axios.post("/api/articles", {
                    'user_id': this.user.id,
                });

                // Show modal after successful creation
                this.showModal = true;
            } catch (error) {
                console.log("Article creation error: ", error);

                if (error.response) {
                if (error.response.data.errors) {
                    this.errorMessages = Object.values(
                    error.response.data.errors
                    ).flat();
                } else {
                    this.errorMessages = error.response.data.message || "Uknown error";
                }
                }
            }
        }
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