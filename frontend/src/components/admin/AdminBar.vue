<template>
    <nav id="admin-navbar">
        <ul>
            <li v-for="item in filteredRoutes" :key="item.id">
                <router-link :to="item.route">
                    {{ item.name }}
                </router-link>
            </li>
        </ul>
    </nav>
</template>
<script>
import axios from "axios";

export default {
    data() {
        return {
            routes: [
                {
                    id: 1,
                    name: "Používatelia",
                    route: '/admin/users',
                },
                {
                    id: 2,
                    name: "Príspevky",
                    route: '/admin/articles',
                },
                {
                    id: 3,
                    name: "Konferencie",
                    route: '/admin/conferences'
                },
                {
                    id: 4,
                    name: "Otázky recenzie",
                    route: '/admin/review_features',
                    roles: ['super_admin'],
                },
                {
                    id: 5,
                    name: "Univerzity",
                    route: '/admin/universities',
                    roles: ['super_admin'],
                },
                {
                    id: 6,
                    name: "Fakulty",
                    route: '/admin/faculties',
                    roles: ['super_admin'],
                },
            ],
            current_user: null,
        };
    },
    computed: {
        filteredRoutes() {
            if (!this.current_user || !this.current_user.role) {
                return this.routes.filter(route => !route.roles);
            }

            return this.routes.filter(route => {
                return !route.roles || route.roles.includes(this.current_user.role.key);
            });
        }
    },
    methods: {
        async getData() {
            const current_user_response = await axios.get("/api/current_user");
            this.current_user = current_user_response.data;
        },
    },
    mounted() {
        this.getData();
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
        color: #52b69a;
        font-weight: 700;
    }

    #admin-navbar a:hover {
        background-color: #52b69a;
        color: #fff;
    }
</style>