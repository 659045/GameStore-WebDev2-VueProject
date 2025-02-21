<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <h1>Cart</h1>
        <div>
            
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'Cart',
    components: {
        NavBar,
    },
    data() {
        return {

        };
    },
    mounted() {

    },
    methods: {

    }
};

</script>

<style scoped>
.content-container {
    height: 100vh;
}
</style>
