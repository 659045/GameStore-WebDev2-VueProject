<template>
    <header>
        <NavBar />
    </header>
    <div class="content-container p-5">
        <h1>Cart</h1>
        <div class="d-flex justify-content-end">
            <router-link to="/payment" class="btn btn-primary btn-lg ms-auto">
                Continue to payment
            </router-link>
        </div>  
        <div class="game-grid mt-5">
            <GameItem v-for="game in games" :game="game" :show-cart-button="false" :show-remove-cart-button="true" @updateCart="fetchCart"/>
        </div>
        <h1 v-show="errorMessage" class="text-center w-100">{{ errorMessage }}</h1>     
        <div v-if="message" class="alert alert-warning text-center">{{ message }}</div>
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
        message: '',
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
            this.games = [];

            try {
                const response = await axios.get(`http://localhost/api/cart`, {
                    params: {
                        user_id: localStorage.getItem('user_id'),
                    },
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                if (response.data.length === 0) {
                    this.message = 'Your cart is empty';
                }

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
