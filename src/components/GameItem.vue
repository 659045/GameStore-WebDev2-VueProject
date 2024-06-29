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
          <label :id="'labelError' + game.id" class="p-2 mt-2 ml-auto label"></label>
        </div>
      </div>
    </div>
  </template>
  
  <script>
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
        role: localStorage.getItem('role')
      };
    },
    computed: {
      showWishlistButton() {
        return (this.role === 'premium' || this.role === 'admin') && !this.ownedGames;
      },
      // isGameInWishlist() {
      //   return this.wishList.some(item => item.game_id === this.game.id);
      // }
    },
    methods: {
      toggleWishlist() {
        const gameId = this.game.id;
        const data = {
          user_id: this.user_id,
          game_id: gameId
        };
  
        const isInWishlist = this.isGameInWishlist;
  
        if (isInWishlist) {
          this.$emit('remove-from-wishlist', gameId, data);

        } else {
          this.$emit('add-to-wishlist', gameId, data);
        }
      },
      addToCart() {
        const gameId = this.game.id;
        const data = { id: gameId };
  
        
      }
    }
  };
  </script>
  
  <style scoped>
  img {
    height: 300px;
    width: 300px;
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
  