<template>
    <div class="landing-wrapper">
        <div id="panel">
            <p class="router-p"><router-link to="/home">Home</router-link></p>
            <p class="router-p"><router-link to="/login">Login</router-link></p>
            <p class="router-p">{{ dummy_email }}</p>
        </div>
        <div id="content-page">
            <p>fewg</p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
        return {
            role: '' //Superadmin, admin, reviewer, student
        }
    }
    }  
</script>

<style>
    .landing-wrapper {
        display: flex;
        flex-direction: row;
        width: 100%;
        background-color: aqua;
    }

    #panel {
        background-color: black;
        height: 100%;
        width: 15%;
        position: fixed;
    }

    .router-p {
        width: 100%;
        height: 3rem;
        background-color: aliceblue;
        border: 1px solid grey;
        padding: 5px;
        font-size: 1.5rem;
        text-align: center; 
        cursor: pointer;
    }

    a:-webkit-any-link {
        text-decoration: none;
    }

    #content-page {
        background-color: blue;
        width: 100%;
        height: 100%;
    }
</style>