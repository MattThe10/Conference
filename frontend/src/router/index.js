
import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from '@/components/LoginForm.vue'
import RegisterForm from '@/components/RegisterForm.vue'
import LandingPage from '@/components/LandingPage.vue'
import ArticlesPage from '@/components/ArticlesPage.vue'

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
        },
        {
            path: '/articles',
            name: 'ArticlesPage',
            component: ArticlesPage
        }

        
    ]

    const router = createRouter({
        history: createWebHistory(process.env.BASE_URL),
        routes
    })

    router.beforeEach(async (to, from, next) => {
        // Check if the route requires authentication
        const isProtectedRoute = to.matched.some(record => record.meta.requiresAuth);

        // Verify if the user is authenticated
        const isAuthenticated = await checkAuth();

        // If the user is authenticated and tries to access the login or register pages,
        // redirect them to the home page
        if (isAuthenticated && (to.path == '/login' || to.path == '/register')) {
            next('home');
        }
        // If the route is protected and the user is not authenticated,
        // redirect them to the login page
        else if (isProtectedRoute && !isAuthenticated) {
            next('/login');
        }
        // In all other cases, proceed to the requested route
        else {
            next();
        }
    });

    export default router



