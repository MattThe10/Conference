<template>
    <div>
        <h2>Zoznam konferencií</h2>
        <div class="conference-wrapper">
            <div class="modal-example">
                <ul>
                    <li v-for="(item, index) in conferences" :key="index">
                        <span>{{ item.name }}</span>
                        <button @click="openModal(index)">Detaily</button>
                    </li>
                </ul>

                <!-- Modal -->
                <div v-if="selectedItem !== null" class="modal-overlay" @click="closeModal">
                    <div class="modal-content" @click.stop>
                        <h2>{{ conferences[selectedItem].name }}</h2>
                        <h3 for="place">Miesto</h3>
                        <p id="place">{{ conferences[selectedItem].place }}</p>
                        <h3 for="street">Ulica</h3>
                        <p id="street">{{ conferences[selectedItem].street }}</p>
                        <h3 for="city">Mesto</h3>
                        <p id="city">{{ conferences[selectedItem].city }}</p>
                        <h3 for="date">Dátum</h3>
                        <p id="date">{{ conferences[selectedItem].date }}</p>
                        <h3 for="misc">Poznámky</h3>
                        <p id="misc">{{ conferences[selectedItem].misc }}</p>
                        <button @click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data() {
        return {
            conferences: [
                {
                    id: 1,
                    name: 'Konferencia 2024',
                    place: 'Univerzita Konštantína Filozofa',
                    street: 'Tr. A. Hlinku 1',
                    city: 'Nitra',
                    date: '13.3.2024',
                    misc: ''
                },
                {
                    id: 2,
                    name: 'Konferencia 2023',
                    place: 'Univerzita Konštantína Filozofa',
                    street: 'Tr. A. Hlinku 1',
                    city: 'Nitra',
                    date: '25.11.2023',
                    misc: 'Učebňa THB103'

                }
            ],
            selectedItem: null, // Tracks the index of the selected item
        }
    },
    methods: {
        openModal(index) {
            this.selectedItem = index; // Set the selected item index
        },
        closeModal() {
            this.selectedItem = null; // Reset the selected item
        },
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
</style>