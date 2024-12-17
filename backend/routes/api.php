<?php

use App\Http\Controllers\ArticleStatusController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewFeatureController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| User Routes (Protected by Authentication Middleware)
|---------------------------------------------------------------------------
|
| - GET /current_user: Returns the information of the current authenticated
| user.
| - POST /update_current_user: Updates the information of the current 
| authenticated user.
|
| These routes are protected by the custom 'Authenticate' middleware,
| which requires users to be authenticated using Sanctum.
|
*/

Route::middleware([Authenticate::class])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/current_user', 'getCurrentUser');
        Route::post('/update_current_user', 'updateCurrentUser');
    });

/*
|---------------------------------------------------------------------------
| University Routes
|---------------------------------------------------------------------------
|
| - GET /universities: Returns a list of all universities.
| - GET /university/{university_id}: Returns a specific university by its
| ID.
|
*/

Route::controller(UniversityController::class)->group(function () {
    Route::get('/universities', 'index');
    Route::get('/universities/{university_id}', 'show');
    Route::post('/universities/store', 'store');
    Route::put('/universities/{university_id}', 'update');
    Route::delete('/universities/{university_id}', 'destroy');

});

/*
|---------------------------------------------------------------------------
| Faculty Routes
|---------------------------------------------------------------------------
|
| - GET /faculties: Returns a list of all faculties.
| - GET /faculty/{faculty_id}: Returns a specific faculty by its ID.
|
*/

Route::controller(FacultyController::class)->group(function () {
    Route::get('/faculties', 'index');
    Route::get('/faculties/{faculty_id}', 'show');
    Route::post('/faculties/store', 'store');
    Route::put('/faculties/{faculty_id}', 'update');
    Route::delete('/faculties/{faculty_id}', 'destroy');

});

/*
|---------------------------------------------------------------------------
| Role Routes
|---------------------------------------------------------------------------
|
| - GET /roles: Returns a list of all roles.
| - GET /role/{role_id}: Returns a specific role by its ID.
|
*/

Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'index');
    Route::get('/role/{role_id}', 'show');
    Route::post('/roles/store', 'store');
    Route::put('/roles/{role_id}', 'update');
    Route::delete('/roles/{role_id}', 'destroy');
});

/*
|---------------------------------------------------------------------------
| ArticleStatus Routes
|---------------------------------------------------------------------------
|
| - GET /article_statuses: Returns a list of all article_statuses.
| - GET /article_status/{article_status_id}: Returns a specific article_status by its ID.
|
*/

Route::controller(ArticleStatusController::class)->group(function () {
    Route::get('/article_statuses', 'index');
    Route::get('/article_status/{article_status_id}', 'show');
    Route::post('/article_statuses/store', 'store');
    Route::put('/article_statuses/{article_status_id}', 'update');
    Route::delete('/article_statuses/{article_status_id}', 'destroy');
});

/*
|---------------------------------------------------------------------------
| Conference Routes
|---------------------------------------------------------------------------
|
| - GET /conferences: Returns a list of all conferences.
| - GET /conference/{conference_id}: Returns a specific conference by its ID.
|
*/

Route::controller(ConferenceController::class)->group(function () {
    Route::get('/conferences', 'index');
    Route::get('/conference/{conference_id}', 'show');
    Route::post('/conferences/store', 'store');
    Route::put('/conferences/{conference_id}', 'update');
    Route::delete('/conferences/{conference_id}', 'destroy');
});

/*
|---------------------------------------------------------------------------
| ReviewFeature Routes
|---------------------------------------------------------------------------
|
| - GET /review_features: Returns a list of all review_features.
| - GET /review_feature/{review_feature_id}: Returns a specific review_feature by its ID.
|
*/

Route::controller(ReviewFeatureController::class)->group(function () {
    Route::get('/review_features', 'index');
    Route::get('/review_feature/{review_feature_id}', 'show');
    Route::post('/review_features/store', 'store');
    Route::put('/review_features/{review_feature_id}', 'update');
    Route::delete('/review_features/{review_feature_id}', 'destroy');
});

/*
|---------------------------------------------------------------------------
| User Routes
|---------------------------------------------------------------------------
|
| - GET /user/{id}/articles: Returns a list of all articles associated with
| a specific user.
|
*/

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{id}/articles', 'getUserArticles');
    Route::get('/users/{id}/articles_for_review', 'getArticlesForReview');
    Route::put('/users/{id}', 'update');
});

/*
|---------------------------------------------------------------------------
| Article Routes
|---------------------------------------------------------------------------
|
| - GET /articles: Returns a list of all articles.
| - GET /articles/{id}: Returns a specific article by its ID.
| - POST /articles: Creates a new article.
| - PUT /articles/{id}: Updates an existing article by its ID.
| - DELETE /articles/{id}: Deletes an article by its ID.
|
*/

Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles', 'index');
    Route::get('/articles/{id}', 'show');
    Route::post('/articles', 'store');
    Route::post('/articles/{id}', 'update');
    Route::delete('/articles/{id}', 'destroy');
    Route::post('/articles/download', 'download');
});

/*
|---------------------------------------------------------------------------
| Review Routes
|---------------------------------------------------------------------------
|
| - GET /reviews: Returns a list of all reviews.
| - GET /reviews/{id}: Returns a specific review by its ID.
| - POST /reviews: Creates a new review.
| - PUT /reviews/{id}: Updates an existing review by its ID.
| - DELETE /reviews/{id}: Deletes an review by its ID.
|
*/

Route::controller(ReviewController::class)->group(function () {
    Route::get('/reviews', 'index');
    Route::get('/reviews/{id}', 'show');
    Route::post('/reviews', 'store');
    Route::put('/reviews/{id}', 'update');
    Route::delete('/reviews/{id}', 'destroy');
});

/*
|---------------------------------------------------------------------------
| Review feature Routes
|---------------------------------------------------------------------------
|
| - GET /feature_values: Returns a list of all review features.
| - GET /feature_values/{id}: Returns a specific review feature by its ID.
| - POST /feature_values: Creates a new review feature.
| - PUT /feature_values/{id}: Updates an existing review feature by its ID.
| - DELETE /feature_values/{id}: Deletes an review feature by its ID.
|
*/

Route::controller(ReviewFeatureController::class)->group(function () {
    Route::get('/feature_values', 'index');
    Route::get('/feature_values/{id}', 'show');
    Route::post('/feature_values', 'store');
    Route::put('/feature_values/{id}', 'update');
    Route::delete('/feature_values/{id}', 'destroy');
});