<template>
    <NavBar></NavBar>
    <h2 class="articles-header">Moje príspevky</h2>
    <div id="articles-wrapper">
        <div id="articles-display">
            <ul>
                <li v-for="dummy in dummies" class="articles-li">
                    <span>{{ dummy.article_name }}</span>
                    <div class="articles-li-div">
                        <span>{{ dummy.article_author }}</span>
                        <span>{{ dummy.article_date }}</span>
                    </div>
                    <hr v-if="dummy.id !== 4">
                </li>

            </ul>
        </div>
    </div>
    <div id="articles-button">
        <button id="btn-insert" @click="showModal = true">Vložiť príspevok</button>
        <ArticlesForm v-model:visible="showModal"></ArticlesForm>
    </div>
</template>
<script>
import NavBar from '@/components/NavBar.vue'
import ArticlesForm from '@/components/ArticlesForm.vue'

export default {
    components: {
        NavBar,
        ArticlesForm
    },
    data() {
        return {
            dummies: [
                {
                    id: 1,
                    article_name: 'React JS a jeho využitie v praxi',
                    article_author: 'Ján Dlhý',
                    article_date: '11-12-24'
                },
                {
                    id: 2,
                    article_name: 'Vue JS a technológie',
                    article_author: 'Patrik Šedivý',
                    article_date: '09-03-22'
                },
                {
                    id: 3,
                    article_name: 'Angular JS a jeho miesto v korporátoch',
                    article_author: 'Tomáš Tóth',
                    article_date: '03-03-24'
                },
                {
                    id: 4,
                    article_name: 'Next JS ako moderný framework',
                    article_author: 'Dávid Dlhoprstý',
                    article_date: '14-09-23'
                },
            ],
            showModal: false,
        }
    }
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