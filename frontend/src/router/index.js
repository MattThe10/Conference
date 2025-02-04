
import { createRouter, createWebHistory } from 'vue-router'
import LoginForm from '@/components/LoginForm.vue'
import RegisterForm from '@/components/RegisterForm.vue'
import ForgotPasswordForm from '@/components/ForgotPasswordForm.vue'
import ResetPasswordForm from '@/components/ResetPasswordForm.vue'
import LandingPage from '@/components/LandingPage.vue'
import ArticlesPage from '@/components/ArticlePage.vue'
import ProfilePage from '@/components/ProfilePage.vue'
import ArticleDetails from '@/components/ArticleDetails.vue'
import ArticleEditForm from '@/components/ArticleEditForm.vue'
import ReviewDetails from '@/components/ReviewDetails.vue'
// import ReviewForm from '@/components/ReviewForm.vue'
import UserDataList from '@/components/admin/users/DataList.vue'
import ArticleDataList from '@/components/admin/articles/DataList.vue'
import ConferenceArticlesDataList from '@/components/admin/articles/DataList.vue'
import ConferenceDataList from '@/components/admin/conferences/DataList.vue'
import ReviewFeatureDataList from '@/components/admin/review_features/DataList.vue'
import UniversityDataList from '@/components/admin/universities/DataList.vue'
import FacultyDataList from '@/components/admin/faculties/DataList.vue'
import ReviewDataList from '@/components/admin/reviews/DataList.vue'

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
            path: '/forgot_password',
            name: 'ForgotPasswordForm',
            component: ForgotPasswordForm
        },
        {
            path: '/password-reset/:token',
            name: 'ResetPasswordForm',
            component: ResetPasswordForm,
            props: route => ({ token: route.params.token, email: route.query.email }),
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
            children: [
                {
                    path: ':articleId',
                    name: 'ArticleDetails',
                    component: ArticleDetails,
                    meta: { requiresAuth: true },
                },
                {
                    path: ':articleId/edit',
                    name: 'ArticleEditForm',
                    component: ArticleEditForm,
                    meta: { requiresAuth: true },
                },
                {
                    path: ':articleId/reviews/:reviewId',
                    name: 'ReviewDetails',
                    component: ReviewDetails,
                    meta: { requiresAuth: true },
                },
                // {
                //     path: ':articleId/reviews/:reviewId/edit',
                //     name: 'ReviewForm',
                //     component: ReviewForm,
                // },
            ],
        },
        {
            path: '/profile',
            name: 'ProfilePage',
            component: ProfilePage,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/users',
            name: 'UserDataList',
            component: UserDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/articles',
            name: 'ArticleDataList',
            component: ArticleDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/articles/:id/reviews',
            name: 'ReviewDataList',
            component: ReviewDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/conferences',
            name: 'ConferenceDataList',
            component: ConferenceDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/conferences/:id/articles',
            name: 'ConferenceArticlesDataList',
            component: ConferenceArticlesDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/review_features',
            name: 'ReviewFeatureDataList',
            component: ReviewFeatureDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/universities',
            name: 'UniversityDataList',
            component: UniversityDataList,
            meta: { requiresAuth: true },
        },
        {
            path: '/admin/faculties',
            name: 'FacultyDataList',
            component: FacultyDataList,
            meta: { requiresAuth: true },
        },
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



