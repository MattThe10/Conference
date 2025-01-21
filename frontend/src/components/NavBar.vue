<template>
    <nav id="navbar">
        <ul id="navbar-ul">
            <li v-for="item in filteredRoutes" :key="item.id" class="navbar-ul-li">
                <span><router-link :to="item.route">{{ item.name }}</router-link>
                </span>
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
                    name: "Domov",
                    route: '/home',
                },
                {
                    id: 2,
                    name: "Príspevky",
                    route: '/articles',
                    roles: ['student', 'reviewer'],
                },
                {
                    id: 3,
                    name: "Admin",
                    route: '/admin/users',
                    roles: ['admin', 'super_admin'],
                },
                {
                    id: 4,
                    name: "Profil",
                    route: '/profile'
                }
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
#navbar {
    position: fixed;
    display: flex;
    top: 0;
    width: 100vw;
    min-height: 6rem;
    background-color: #52b69a;
    align-items: center;
    justify-content: center;
    color: #fefae0;
}

#navbar-ul {
    width: 100vw;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    list-style: none;
    font-size: 2rem;
}

.navbar-ul-li {}

.navbar-ul-li a:visited {
    color: #fefae0
}
</style>
