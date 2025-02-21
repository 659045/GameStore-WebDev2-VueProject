import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import NotFound from '../views/404NotFound.vue'
import Login from '../views/Login.vue'
import SignUp from '../views/SignUp.vue'
import OwnedGames from '@/views/OwnedGames.vue'
import GameManagement from '@/views/GameManagement.vue'
import User from '@/views/User.vue'
import Wishlist from '@/views/Wishlist.vue'
import Cart from '@/views/Cart.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/signup',
      name: 'SignUp',
      component: SignUp
    },
    {
      path: '/owned-games',
      name: 'OwnedGames',
      component: OwnedGames
    },
    {
      path: '/manage-games',
      name: 'ManageGames',
      component: GameManagement
    },
    {
      path: '/user',
      name: 'User',
      component: User
    },
    {
      path: '/wishlist',
      name: 'Wishlist',
      component: Wishlist
    },
    {
      path: '/cart',
      name: 'Cart',
      component: Cart
    },
    {
      path: '/404',
      name: 'NotFound',
      component: NotFound
    }
  ]
})

export default router
