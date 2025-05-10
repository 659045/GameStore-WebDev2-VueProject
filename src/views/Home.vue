<template>
  <header>
    <NavBar />
  </header>
  <div>
    <img class="front-image" src="https://media.gq-magazine.co.uk/photos/645b5c3c8223a5c3801b8b26/16:9/w_1280,c_limit/100-best-games-hp-b.jpg">
    <div class="game-grid mt-5">
        <GameItem v-for="game in games" :game="game" :wishList="wishList" :ownedGames="ownedGames" :show-wishlist-button="showWishlistButton" @updateWishlist="fetchWishList"/>
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
        showWishlistButton: localStorage.getItem('role') !== 'normal',
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

        this.fetchWishList();
        this.fetchOwnedGames();
      } catch (error) {
        this.errorMessage = 'Error fetching games';
      }
    },
    methods: {
      async fetchWishList() {
        const response = await axios.get('http://localhost/api/wishlist', {
          params: {
            user_id: localStorage.getItem('user_id'),
          },
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        })

        this.wishList = response.data;
      },
      async fetchOwnedGames() {
        const response = await axios.get('http://localhost/api/owned', {
          params: {
            user_id: localStorage.getItem('user_id'),
          },
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        })

        this.ownedGames = response.data;
      }
    }
};
</script>

<style scoped>
  .front-image {
    height: 500px;
    width: 100%;
    display: block;
    margin: 1% auto 5% auto
  }

  .game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }
</style>