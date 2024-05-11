<template>
    <div class="container">
      <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
          <li class="nav-item"><router-link to="/" class="nav-link">Home</router-link></li>
          <li v-if="isLoggedIn" class="nav-item ml-auto"><router-link to="/owned" class="nav-link">My Games</router-link></li>
          <li v-if="isLoggedIn" class="nav-item"><router-link to="/cart" class="nav-link">Shopping Cart</router-link></li>
          <li v-if="isAdmin" class="nav-item ml-auto"><router-link to="/game" class="nav-link">Manage games</router-link></li>
          <li v-if="isPremium" class="nav-item ml-auto"><router-link to="/wishlist" class="nav-link">Wish List</router-link></li>
          <li v-if="isNormal" class="nav-item ml-auto"><router-link to="/user/upgrade" class="nav-link">Upgrade to Premium</router-link></li>
          <li v-if="isLoggedIn" class="nav-item" @click="logout"><router-link to="" class="nav-link">Logout</router-link></li>
          <li v-if="isLoggedIn" class="nav-item">
            <router-link to="/user" class="userAccountLink">
              <button class="btn btn-primary mt-0" style="width: 100%;">
                <i class="fa fa-user userIcon mr-2"></i>{{ username }}
              </button>
            </router-link>
          </li>
          <li v-if="!isLoggedIn" class="nav-item"><router-link to="/login" class="nav-link">Login</router-link></li>
          <li v-if="!isLoggedIn" class="nav-item"><router-link to="/signup"><button class="btn btn-primary mt-0" style="width: 100%"><i class="fa fa-user userIcon mr-2"></i>Sign Up</button></router-link></li>
        </ul>
      </header>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NavBar',
  data() {
    return {
      username: localStorage.getItem('username'),
      isLoggedIn: localStorage.getItem('isLoggedIn') ? true : false,
      isAdmin: localStorage.getItem('role') === 'admin',
      isPremium: localStorage.getItem('role') === 'premium',
      isNormal: localStorage.getItem('role') === 'normal',
    };
  },
  methods: {
    logout() {
      axios.post('http://localhost/api/logout').then(() => {
        this.$router.push('/');
        localStorage.clear();
      });
    },
  },
};
</script>

<style>
  .nav-item {
    margin: 0px 5px 0px 5px;

    :hover {
      background-color: lightgray;
    }
  }

  .userIcon {
    font-size: 20px;
  }

  .userAccountLink {
    color: white;
  }
</style>