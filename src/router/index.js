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
import Upgrade from '@/views/Upgrade.vue'

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
      component: OwnedGames,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/manage-games',
      name: 'ManageGames',
      component: GameManagement,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/user',
      name: 'User',
      component: User,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/wishlist',
      name: 'Wishlist',
      component: Wishlist,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/cart',
      name: 'Cart',
      component: Cart,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/user/upgrade',
      name: 'Upgrade',
      component: Upgrade,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/404',
      name: 'NotFound',
      component: NotFound
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    const isLoggedIn = JSON.parse(localStorage.getItem('isLoggedIn'))
    if (!isLoggedIn) {
      next({ name: 'NotFound' })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
