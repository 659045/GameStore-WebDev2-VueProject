<script setup>
    import { RouterView } from 'vue-router'
    import Footer from './components/Footer.vue';
</script>

<template>
    <head>  
        <title>Game Store</title>
        <link 
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" 
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
            crossorigin="anonymous"
        >

        <link rel="stylesheet" href="/css/label.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <RouterView />
    <Footer />
</template>

<style>
.nav-item {
    margin: 0px 5px 0px 5px;

    :hover {
      background-color: lightgray;
    }
}

.userIcon {
    font-size: 20px;
}

.userAccountLink {
    color: white;
}
</style>
