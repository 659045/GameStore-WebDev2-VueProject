<template>
<header>
  <NavBar />
</header>

<div class="content-container p-5">
  <h1 class="mb-4">Payment Summary</h1>
<div v-if="errorMessage" class="alert alert-warning text-center">{{ errorMessage }}</div>

<div v-for="game in games" :key="game.id" class="card mb-3">
    <div class="row g-0">
        <div class="col-md-3">
            <img :src="'/../../../app/public/img/' + game.image" :alt="game.title" />
        </div>
        <div class="col-md-9">
            <div class="card-body">
                <h5 class="card-title">{{ game.title }}</h5>
                <p class="card-text text-muted">{{ game.description }}</p>
                <p class="card-text fw-bold">€{{ game.price }}</p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center">
<h4>Total: €{{ totalPrice }}</h4>
<button class="btn btn-primary btn-lg" @click="pay">Pay</button>
</div>

<div v-if="successMessage" class="alert alert-success mt-4">{{ successMessage }}</div>
<button v-if="paymentSuccess" class="btn btn-success mt-4" @click="$router.push('/')">Return to Home</button>
</div>
</template>
  


<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default {
name: 'Payment',
components: {
    NavBar,
},
data() {
    return {
        games: [],
        errorMessage: '',
        successMessage: '',
        paymentSuccess: false,
    };
},
mounted() {
    this.fetchCart();
},
computed: {
    totalPrice() {
        return this.games.reduce((total, game) => total + parseFloat(game.price), 0);
    },
},
methods: {
    async fetchCart() {
        this.games = [];
        this.errorMessage = '';
        this.successMessage = '';

        try {
        const response = await axios.get(`http://localhost/api/cart`, {
            params: {
            user_id: localStorage.getItem('user_id'),
            },
            headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });

        if (response.data.length === 0) {
            this.errorMessage = 'Your cart is empty';
            return;
        }

        for (const item of response.data) {
            const gameResponse = await axios.get(`http://localhost/api/game`, {
            params: { id: item.game_id },
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
            });
            this.games.push(gameResponse.data);
        }
        } catch (error) {
        this.errorMessage = 'Error fetching cart';
        }
    },
    async pay() {
        this.successMessage = 'Payment successful! Thank you for your purchase.';
        await this.addToOwnedGames();
        await this.removeGameFromWishlist();
        await this.resetCart();
        this.paymentSuccess = true;
    },
    async resetCart() {
        try {
        await axios.delete(`http://localhost/api/cart`, {
            data: {
                user_id: localStorage.getItem('user_id'),
            },
            headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });

        this.games = [];
        } catch (error) {
        this.errorMessage = 'Error resetting cart';
        }
    },
    async removeGameFromWishlist() {
        try {
            for (const game of this.games) {
                await axios.delete(`http://localhost/api/wishlist`, {
                    data: {
                        user_id: localStorage.getItem('user_id'),
                        game_id: game.id,
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                });
            }
        } catch (error) {
            this.errorMessage = 'Error removing game from wishlist';
        }
    },
    async addToOwnedGames() {
        try {
            for (const game of this.games) {
                await axios.post('http://localhost/api/owned', {
                    user_id: localStorage.getItem('user_id'),
                    game_id: game.id,
                }, {
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                });
            }
        } catch (error) {
            this.errorMessage = 'Error adding games to owned';
        }
    }
},
};
</script>
  

<style scoped>
img {
    max-height: 180px;
    object-fit: cover;
}
</style>