<template>
    <head>
        <title>404 Not Found</title>
    </head>
    <header>
        <NavBar />
    </header>
    <div class="content-container">
        <h1>404 Not Found</h1>
    </div>
</template>

<script>
import NavBar from '../components/NavBar.vue';

export default {
    name: '404NotFound',
    components: {
        NavBar,
    },
};
</script>

<style scoped>
    .content-container {
        display: flex;
        justify-content: center;
        height: 100vh;
    }
</style>