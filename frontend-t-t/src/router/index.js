import { createRouter, createWebHistory } from 'vue-router'
import LandingPage from '../views/LandingPage.vue'
import LoginPage from '../views/LoginView.vue'
const routes = [
  {
    path: '/',
    name: 'Landing',
    component: LandingPage
  },
  { 
    path: '/login', 
    name: 'Login', 
    component: LoginPage 
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
