<template>
    <header>
        <NavBar />
    </header>

    <div class="content-container p-5">
        <h1>Payment</h1>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-lg ms-auto">Pay</button>
        </div>  
    </div>

</template>


<script>
import axios from 'axios';
import NavBar from '../components/NavBar.vue';

export default{
    name: 'Payment',
    data() {
        return {
            games: [],
            errorMessage: '',
        };
    },
    components: {
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
                    this.errorMessage = 'Your cart is empty';
                }

                response.data.forEach((game) => {
                    axios.get(`http://localhost/api/game`, {
                        params: {
                            id: game.game_id,
                        },
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        },
                    }).then((response) => {
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

</style>