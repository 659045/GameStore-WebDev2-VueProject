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
            <button @click="addToCart" class="btn btn-primary w-50 ml-auto add-to-cart-button" :value="game.id">
              Add to cart
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
      showWishlistButton: Boolean
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
      toggleWishlist() {
        if (this.isGameInWishlist) {
          this.removeFromWishlist();
        } else {
          this.addToWishlist();
        }
      },
      async addToWishlist() {
        try {
          await axios.post('http://localhost/api/wishlist', {
            user_id: localStorage.getItem('user_id'),
            game_id: this.game.id,
          },
          {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
          });

          this.successMessage = 'Game added to wishlist';
          this.resetMessages();
          this.$emit('updateWishlist');
        } catch (error) {
          this.errorMessage = error.response.data.message;
          this.resetMessages();
        }
      },
      async removeFromWishlist() {
        try {
          await axios.delete('http://localhost/api/wishlist', {
            data: {
              game_id: this.game.id,
              user_id: localStorage.getItem('user_id'),
            },
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
          });

          this.successMessage = 'Game removed from wishlist';
          this.resetMessages();
        } catch (error) {
          this.errorMessage = error.response.data.message;
          this.resetMessages();
        }
      },
      async addToCart() {
        try {
          await axios.post('http://localhost/api/cart', {
            id: this.game.id,
          },
          {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
          });

          axios.get(`http://localhost/api/cart`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
          });

          this.successMessage = 'Game added to cart';
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
  