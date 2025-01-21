<template>
    <div>
        <h2>{{ conference.name }}</h2>
        <div class="conference-wrapper">
            <div class="modal-example">
                <div>
                    <h3>Abstrakt</h3>
                    <p>
                        {{ conference.abstract }}
                    </p>
                </div>
                <div>
                    <h3>Dátum konferencie</h3>
                    <p>
                        {{
                            new Date(conference.conference_date).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                            })
                        }}
                    </p>
                </div>
                <div>
                    <h3>Deadline</h3>
                    <p>
                        {{
                            new Date(conference.submission_deadline).toLocaleDateString('sk-SK', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                            })
                        }}
                    </p>
                </div>

                <button @click="$emit('openForm', conference)" class="btn-add article-edit-btn btn" :class="{ disabled: isDisabled(conference) }">
                    +
                </button>

                <!-- Modal -->
                <!-- <div v-if="selectedItem !== null" class="modal-overlay" @click="closeModal">
                    <div class="modal-content" @click.stop>
                        <h2>{{ conferences[selectedItem].name }}</h2>
                        <h3 for="place">Miesto</h3>
                        <p id="place">{{ conferences[selectedItem].place }}</p>
                        <h3 for="street">Ulica</h3>
                        <p id="street">{{ conferences[selectedItem].street }}</p>
                        <h3 for="city">Mesto</h3>
                        <p id="city">{{ conferences[selectedItem].city }}</p>
                        <h3 for="postal-code">PSČ</h3>
                        <p id="postal-code">{{ conferences[selectedItem].postal_code }}</p>
                        <h3 for="date">Dátum</h3>
                        <p id="date">{{ conferences[selectedItem].date }}</p>
                        <h3 for="misc">Poznámky</h3>
                        <p id="misc">{{ conferences[selectedItem].misc }}</p>
                        <button @click="closeModal">Close</button>
                        <h2>{{ conferences[selectedItem].name }}</h2>
                        <h3>Miesto</h3>
                        <p>{{ conferences[selectedItem].place }}</p>
                        <p>{{ conferences[selectedItem].street }}</p>
                        <p>{{ conferences[selectedItem].postal_code }}</p>
                        <p>{{ conferences[selectedItem].city }}</p>
                        <h3>Dátum konferencie</h3>
                        <p>{{ conferences[selectedItem].conference_date }}</p>
                        <h3>Deadline</h3>
                        <p>{{ conferences[selectedItem].submission_deadline }}</p>

                        <router-link to="/" :class="{ disabled: isDisabled(conferences[selectedItem]) }">Nahrať príspevok</router-link>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            // conferences: [
            //     {
            //         id: 1,
            //         name: 'Konferencia 2024',
            //         place: 'Univerzita Konštantína Filozofa',
            //         street: 'Tr. A. Hlinku 1',
            //         city: 'Nitra',
            //         date: '13.3.2024',
            //         misc: ''
            //     },
            //     {
            //         id: 2,
            //         name: 'Konferencia 2023',
            //         place: 'Univerzita Konštantína Filozofa',
            //         street: 'Tr. A. Hlinku 1',
            //         city: 'Nitra',
            //         date: '25.11.2023',
            //         misc: 'Učebňa THB103'

            //     }
            // ],
            conferences: [],
            selectedItem: null, // Tracks the index of the selected item
            user: [],
            articles: [],
        }
    },
    props: {
        conference: Object,
    },
    methods: {
        openModal(index) {
            this.selectedItem = index; // Set the selected item index
        },
        closeModal() {
            this.selectedItem = null; // Reset the selected item
        },
        async getConferences() {
            const conferences_response = await axios.get("/api/conferences");
            this.conferences = conferences_response.data;

            this.conferences = conferences_response.data.map(element => ({
                id: element['id'],
                name: 'Konferencia ' + element['end_year'] + ' / ' + element['end_year'],
                place: element['university']['name'],
                street: element['university']['address'],
                city: element['university']['city'],
                postal_code: element['university']['postal_code'],
                conference_date: new Date(element['conference_date']).toLocaleDateString('sk-SK', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }),
                submission_deadline: new Date(element['submission_deadline']).toLocaleDateString('sk-SK', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                }),
            }));
        },
        isDisabled(conference) {
            const deadline_date = new Date(conference['submission_deadline']);
            const current_date = new Date();

            if (current_date > deadline_date) return true;

            return false;
        },
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
    },
    mounted() {
        this.getConferences();
        this.getData();
    },
}
</script>
<style scoped>
#btn-details {
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    max-width: 500px;
    width: 90%;
}

.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.btn-add {
    padding: 10px;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    width: 100%;
}
</style>