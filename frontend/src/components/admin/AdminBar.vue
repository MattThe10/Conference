<template>
    <nav id="admin-navbar">
        <ul>
            <li v-for="item in routes" :key="item.id">
                <router-link :to="item.route">
                    {{ item.name }}
                </router-link>
            </li>
        </ul>
    </nav>
</template>
<script>
export default {
    data() {
        return {
            routes: [
                {
                    id: 1,
                    name: "Používatelia",
                    route: '/admin/manage/users',
                },
                {
                    id: 2,
                    name: "Príspevky",
                    route: '/admin/manage/articles',
                },
                {
                    id: 3,
                    name: "Konferencie",
                    route: '/admin/manage/conferences'
                },
            ],
        };
    },
};
</script>
<style scoped>
    #admin-navbar {
        position: fixed;
        display: flex;
        flex-direction: column;
        top: 6rem;
        width: 15vw;
        height: 90vh;
        min-height: 6rem;
        align-items: center;
        color: #fefae0;
    }

    #admin-navbar ul {
        list-style: none;
        width: 100%;
    }

    #admin-navbar li {
        width: 100%;
    }

    #admin-navbar a {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 3rem;
        text-decoration: none;
    }
</style>
