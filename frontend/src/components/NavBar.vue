<template>
    <nav id="navbar">
        <ul id="navbar-ul">
            <li v-for="item in routes" :key="item.id" class="navbar-ul-li">
                <span><router-link :to="item.route">{{ item.name }}</router-link>
                </span>
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
                    name: "Domov",
                    route: '/home',
                },
                {
                    id: 2,
                    name: "Príspevky",
                    route: '/articles',
                },
            ],
        };
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
