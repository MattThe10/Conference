
import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from '@/components/LoginForm.vue'
import RegisterForm from '@/components/RegisterForm.vue'
import LandingPage from '@/components/LandingPage.vue'

const routes = [
         {
             path: '/',
             redirect: '/login'
         },
        {
            path: '/login',
            name: 'LoginForm',
            component: LoginForm
        },
        {
            path: '/register',
            name: 'RegisterForm',
            component: RegisterForm
        },
        {
            path: '/home',
            name: 'LandingPage',
            component: LandingPage
        }
    ]

    const router = createRouter({
        history: createWebHistory(process.env.BASE_URL),
        routes
    })

    export default router



