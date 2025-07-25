<template>
    <div class="col-lg-10">
      <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <template v-if="showWishlistButton">
                <button @click="toggleWishlist" class="btn btn-primary w-25 ml-auto wishlist-button mb-3" :value="game.id">
                <i :class="['fa', isGameInWishlist ? 'fa-heart' : 'fa-heart-o']"></i>
                </button>
            </template>
            <img :src="'/../../../app/public/img/' + game.image" />
            <small class="text-muted mt-3">Title</small>
            <p>{{ game.title }}</p>
            <small class="text-muted">Description</small>
            <p>{{ game.description }}</p>
            <small class="text-muted">Price</small>
            <p>{{ game.price }}</p>
            <template v-if="isLoggedIn">
                <button v-if="showCartButton" @click="addToCart" class="btn btn-primary w-50 ml-auto add-to-cart-button" :value="game.id">
                    Add to cart
                </button>
                <button v-if="showRemoveCartButton" @click="removeFromCart" class="btn btn-danger w-50 ml-auto add-to-cart-button" :value="game.id">
                    Remove from cart
                </button>
                <button v-if="showRemoveWishlistButton" @click="removeFromWishlist" class="btn btn-danger w-50 ml-auto add-to-cart-button" :value="game.id">
                    Remove from wishlist
                </button>
            </template>
            <template v-else>
                <button @click="route('/login')" class="btn btn-primary w-50 ml-auto add-to-cart-button">
                    Buy
                </button>
            </template>
            <transition name="fade">
                <label v-show="errorMessage" class="text-center w-100 mt-3 alert alert-danger">{{ errorMessage }}</label>     
            </transition>
            <transition name="fade">
                <label v-show="successMessage" class="text-center w-100 mt-3 alert alert-success">{{ successMessage }}</label>
            </transition>
        </div>
      </div>
    </div>
  </template>
  
<script>

import axios from 'axios';

export default {
    name: 'GameItem',
    props: {
        game: Object,
        wishList: Array,
        ownedGames: Array,
        showWishlistButton: Boolean,
        showRemoveWishlistButton: Boolean,
        showCartButton: Boolean,
        showRemoveCartButton: Boolean,
    },
    data() {
      return {
        isLoggedIn: localStorage.getItem('isLoggedIn'),
        role: localStorage.getItem('role'),
        errorMessage: '',
        successMessage: '',
      };
    },
    computed: {
        isGameInWishlist() {
            return this.wishList.some((game) => game.game_id === this.game.id);
        },
    },
    methods: {
        async toggleWishlist() {
            if (this.isGameInWishlist) {
                await this.removeFromWishlist();
            } else {
                await this.addToWishlist();
            }
        },
        async addToWishlist() {
            const alreadyOwned = await this.isOwnedGame();

            if (alreadyOwned) {
                this.errorMessage = 'Game is already owned';
                this.resetMessages();
                return;
            }

            try {
                const wishListResponse = await axios.get('http://localhost/api/wishlist', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    params: {
                        user_id: localStorage.getItem('user_id')
                    },
                });

                const wishListItems = wishListResponse.data;
                const alreadyInWishlist = wishListItems.some(item => item.game_id === this.game.id);

                if (alreadyInWishlist) {
                    this.errorMessage = 'Game is already in the wishlist';
                    this.resetMessages();
                    return;
                }

                const response = await axios.post('http://localhost/api/wishlist', {
                    user_id: localStorage.getItem('user_id'),
                    game_id: this.game.id,
                },
                {
                    headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                if (response.status === 200) {
                    this.successMessage = 'Game added to wishlist';
                    this.$emit('updateWishList');
                }
                this.resetMessages();

                this.isGameInWishlist = true;
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        async removeFromWishlist() {
            try {
                const response = await axios.delete('http://localhost/api/wishlist', {
                    data: {
                        game_id: this.game.id,
                        user_id: localStorage.getItem('user_id'),
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                if (response.status === 200) {
                    this.successMessage = 'Game removed from wishlist';
                    this.$emit('updateWishList');
                }

                this.resetMessages();
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        async addToCart() {
            try {
                const alreadyInCart = await this.isGameInCart();
                const alreadyOwned = await this.isOwnedGame();

                if (alreadyInCart) {
                    this.errorMessage = 'Game is already in the cart';
                    this.resetMessages();
                    return;
                }

                if (alreadyOwned) {
                    this.errorMessage = 'Game is already owned';
                    this.resetMessages();
                    return;
                }
                
                const response = await axios.post('http://localhost/api/cart', {
                    game_id: this.game.id,
                    user_id: localStorage.getItem('user_id'),
                },
                {
                    headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                if (response.status === 201) {
                    this.successMessage = 'Game added to cart';
                    this.$emit('updateCart');
                }

                this.resetMessages();
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        async removeFromCart() {
            try {
                const response = await axios.delete('http://localhost/api/cart', {
                    headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    data: {
                    game_id: this.game.id,
                    user_id: localStorage.getItem('user_id'),
                    },
                });

                if (response.status === 200) {
                    this.successMessage = 'Game removed from cart';
                    this.$emit('updateCart');
                }

                this.resetMessages();
            } catch (error) {
                this.errorMessage = error.response.data.message;
                this.resetMessages();
            }
        },
        async isGameInCart() {
            const cartResponse = await axios.get(`http://localhost/api/cart`, {
                headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
                params: {
                user_id: localStorage.getItem('user_id')
                },
            });

            const cartItems = cartResponse.data;
            return cartItems.some(item => item.game_id === this.game.id);
        },
        async isOwnedGame() {
            const response = await axios.get('http://localhost/api/owned', {
            params: {
                user_id: localStorage.getItem('user_id'),
            },
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
            })

            return response.data.some(item => item.game_id === this.game.id);
        },
        route(path) {
            this.$router.push(path);
        },
        resetMessages() {
            setTimeout(() => {
                this.errorMessage = '';
                this.successMessage = '';
            }, 3000);
        },
        }
};
</script>
  
<style scoped>
img {
    height: 300px;
    width: 250px;
    display: block;
    margin: 0 auto;
}

p {
    overflow: hidden;
    width: 200px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;   
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.alert {
    transition: opacity 1s ease-in-out;
}
</style>
  