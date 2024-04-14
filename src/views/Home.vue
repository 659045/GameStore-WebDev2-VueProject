<script setup>
    import GameItem from '../components/GameItem.vue';
    import { ref } from 'vue';
    import { useGameStore } from '../stores/gameStore.js';
    import axios from 'axios';
</script>

<template>
    <div class="content-container">
        <img class="front-image" src="https://media.gq-magazine.co.uk/photos/645b5c3c8223a5c3801b8b26/16:9/w_1280,c_limit/100-best-games-hp-b.jpg">
    </div>
    <div class="game-grid">
        <GameItem v-for="game in games" :key="game.id" :game="game" />
    </div>
</template>

<script>
export default {
    name: 'Home',
    data() {
    return {
        games: [],
    };
    },
    components: {
        GameItem,
    },
    mounted() {
        axios.get('http://localhost/api/game').then((response) => {
            this.games = response.data;
        });

        console.log(this.games);
    },
    methods: {
        addToCart(gameId, data) {
            console.log('Added to cart', gameId, data);
        },
        addToWishlist(gameId, data) {
            console.log('Added to wishlist', gameId, data);
        },
        removeFromWishlist(gameId, data) {
            console.log('Removed from wishlist', gameId, data);
        },
    },
};
</script>

<style>
  .front-image {
    height: 500px;
    width: 100%;
    display: block;
    margin: 1% auto 5% auto
  }

  p {
    overflow: hidden;
    width: 200px;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  img {
    height: 300px;
    width: 300px;
    display: block;
    margin: 0 auto;
  }

  .content-container {
    height: 100vh;
  }

  .game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }
</style>