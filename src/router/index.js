import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import NotFound from '../views/404NotFound.vue'
import Login from '../views/Login.vue'
import SignUp from '../views/SignUp.vue'
import OwnedGames from '@/views/OwnedGames.vue'

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
      path: '/owned-games',
      name: 'OwnedGames',
      component: OwnedGames
    },
    {
      path: '/signup',
      name: 'SignUp',
      component: SignUp
    },
    {
      path: '/404',
      name: 'NotFound',
      component: NotFound
    }
  ]
})

export default router
