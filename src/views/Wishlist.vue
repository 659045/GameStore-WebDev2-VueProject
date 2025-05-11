<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container p-5">
        <h1>Wishlist</h1>
        <div class="game-grid mt-5">
            <GameItem v-for="game in games" :game="game" :show-wishlist-button="false" :show-remove-wishlist-button="true" @updateWishlist="fetchWishList"/>
        </div>
        <label v-show="errorMessage" class="text-center w-100 alert alert-danger">{{ errorMessage }}</label>     
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import GameItem from '../components/GameItem.vue';

export default {
    name: 'Wishlist',
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
        this.fetchWishList();
    },
    methods: {
        async fetchWishList() {
            this.games = [];

            try {
                const response = await axios.get(`http://localhost/api/wishlist`, {
                    params: {
                        user_id: localStorage.getItem('user_id'),
                    },
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                })

                response.data.forEach((game) => {
                    axios.get(`http://localhost/api/game`, {
                        params: {
                            id: game.game_id,
                        },
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        }
                    })  
                    .then((response) => {
                        this.games.push(response.data);
                    });
                });
            } catch (error) {
                this.errorMessage = 'Error fetching cart';
            }
        },
    },
}
</script>

<style scoped>
  .content-container {
    height: 150vh;
    width: 100%;
  }

  .game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
  }
</style>
