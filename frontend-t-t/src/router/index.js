import { createRouter, createWebHistory } from 'vue-router'
import LandingPage from '../views/LandingPage.vue'
import LoginPage from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
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
  { 
    path: '/register', 
    name: 'Register', 
    component: RegisterView 
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
