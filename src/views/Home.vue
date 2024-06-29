<template>
  <header>
    <NavBar />
  </header>
  <div>
    <img class="front-image" src="https://media.gq-magazine.co.uk/photos/645b5c3c8223a5c3801b8b26/16:9/w_1280,c_limit/100-best-games-hp-b.jpg">
  
    <div class="game-grid mt-5">
        <GameItem v-for="game in games" :key="game.id" :game="game" />
    </div>
    <label v-show="errorMessage" class="label mx-auto">{{ errorMessage }}</label>
  </div>
</template>

<script>
import axios from 'axios';
import GameItem from '../components/GameItem.vue';
import NavBar from '../components/NavBar.vue';

export default {
    name: 'Home',
    data() {
      return {
        games: [],
        errorMessage: '',
      };
    },
    components: {
        GameItem,
        NavBar,
    },
    mounted() {
      try {
        axios.get('http://localhost/api/game').then((response) => {
          this.games = response.data;
        });
      } catch (error) {
        this.errorMessage = 'Error fetching games';
      }
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