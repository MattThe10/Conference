
import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from '@/components/LoginForm.vue'
import RegisterForm from '@/components/RegisterForm.vue'
import LandingPage from '@/components/LandingPage.vue'

import { checkAuth } from '@/auth';

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
            component: LandingPage,
            meta: { requiresAuth: true },
        }
    ]

    const router = createRouter({
        history: createWebHistory(process.env.BASE_URL),
        routes
    })

    router.beforeEach(async (to, from, next) => {
        const isProtectedRoute = to.matched.some(record => record.meta.requiresAuth);
        if (isProtectedRoute) {
            const isAuthenticated = await checkAuth();
            if (!isAuthenticated) {
                next('/login');
            }
        }
        next();
    });

    export default router



