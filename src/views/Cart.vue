<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container p-5">
        <h1>Cart</h1>
        <div class="game-grid mt-5">
            <GameItem v-for="game in games" :game="game"/>
        </div>
        <h1 v-show="errorMessage" class="text-center w-100">{{ errorMessage }}</h1>     
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';
import GameItem from '../components/GameItem.vue';

export default {
    name: 'Cart',
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
        this.fetchCart();
    },
    methods: {
        async fetchCart() {
            try {
                const response = await axios.get(`http://localhost/api/cart`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                if (response.data.length === 0) {
                    this.errorMessage = 'Your cart is empty';
                }

                response.data.forEach((gameId) => {
                    axios.get(`http://localhost/api/game?id=${gameId}`, {
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
