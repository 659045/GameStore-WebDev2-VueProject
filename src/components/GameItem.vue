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
import { render } from 'vue';

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
      isGameOwned() {
        return this.ownedGames.some((game) => game.game_id === this.game.id);
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
        if (this.isGameOwned) {
          this.errorMessage = 'You already own this game';
          this.resetMessages();
          return;
        }

        try {
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
          const response = await axios.post('http://localhost/api/cart',
          {
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
            this.successMessage = 'Game removed from cart';
            this.$emit('updateCart');
          }

          this.resetMessages();
        } catch (error) {
          this.errorMessage = error.response.data.message;
          this.resetMessages();
        }
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
  