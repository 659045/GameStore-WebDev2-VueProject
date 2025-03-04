<template>
    <div class="col-lg-10">
      <div class="card mb-5">
        <div class="card-body d-flex flex-column">
          <template v-if="showWishlistButton">
            <button @click="toggleWishlist" class="btn btn-primary w-25 ml-auto wishlist-button mb-3">
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
          <label v-show="errorMessage" class="text-center w-100 alert alert-danger">{{ errorMessage }}</label>     
          <label v-show="successMessage" class="text-center w-100 alert alert-success">{{ successMessage }}</label>
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
      ownedGames: Boolean
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
      showWishlistButton() {
        return (this.role === 'premium' || this.role === 'admin') && !this.ownedGames;
      },
      isGameInWishlist() {
        
      }
    },
    methods: {
      toggleWishlist() {
        const gameId = this.game.id;
        const data = {
          user_id: this.user_id,
          game_id: gameId
        };

        // const isInWishlist = this.isGameInWishlist;

        // if (isInWishlist) {
        //   this.$emit('remove-from-wishlist', gameId, data);

        // } else {
        //   this.$emit('add-to-wishlist', gameId, data);
        // }
      },
      addToCart() {
        console.log(this.game.id);
        try {
          axios.post('http://localhost/api/cart', {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
            },
            data: {
                id: gameId,
            },
          })
        } catch (error) {
          
        }


      },
      route(path) {
        this.$router.push(path);
      }
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
  </style>
  