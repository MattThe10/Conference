
import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from '@/components/LoginForm.vue'
import RegisterForm from '@/components/RegisterForm.vue'
import LandingPage from '@/components/LandingPage.vue'
import ArticlesPage from '@/components/ArticlesPage.vue'
import ArticlesForReviewPage from '@/components/ArticlesForReviewPage.vue'
import ArticleForReviewDetailsPage from '@/components/ArticleForReviewDetailsPage.vue'
import ReviewFormPage from '@/components/ReviewFormPage.vue'
import ReviewDetailsPage from '@/components/ReviewDetailsPage.vue'
import ProfilePage from '@/components/ProfilePage.vue'
import ArticleFormPage from '@/components/ArticleFormPage.vue'
import UserListPage from '@/components/admin/UserListPage.vue'
import ArticleListPage from '@/components/admin/ArticleListPage.vue'
import ConferenceListPage from '@/components/admin/ConferenceListPage.vue'
import ArticleDetailsPage from '@/components/ArticleDetailsPage.vue'

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
            component: ArticlesPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/articles_for_review',
            name: 'ArticlesForReviewPage',
            component: ArticlesForReviewPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/articles_for_review/:articles_for_review_id',
            name: 'ArticleForReviewDetailsPage',
            component: ArticleForReviewDetailsPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/articles_for_review/:articles_for_review_id/reviews/:review_id/review_form',
            name: 'ReviewFormPage',
            component: ReviewFormPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/articles/:article_id',
            name: 'ArticleDetailsPage',
            component: ArticleDetailsPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/articles/:article_id/reviews/:review_id',
            name: 'ReviewDetailsPage',
            component: ReviewDetailsPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/profile',
            name: 'ProfilePage',
            component: ProfilePage,
            meta: { requiresAuth: true },
        },
        {
            path: '/conferences/:conference_id/articles/:article_id/update',
            name: 'ArticleFormPage',
            component: ArticleFormPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/manage/users',
            name: 'UserListPage',
            component: UserListPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/manage/articles',
            name: 'ArticleListPage',
            component: ArticleListPage,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/manage/conferences',
            name: 'ConferenceListPage',
            component: ConferenceListPage,
            meta: { requiresAuth: true },
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



