<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\FacultyController;
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
    Route::get('/university/{university_id}', 'show');
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
    Route::get('/faculty/{faculty_id}', 'show');
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
});


/*
|---------------------------------------------------------------------------
| Conference Routes
|---------------------------------------------------------------------------
|
| - GET /conferences: Returns a list of all conferences.
| - GET /conferences/{id}: Returns a specific conference by its ID.
|
*/

Route::controller(ConferenceController::class)->group(function () {
    Route::get('/conferences', 'index');
    Route::get('/conferences/{id}', 'show');
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
    Route::put('/articles/{id}', 'update');
    Route::delete('/articles/{id}', 'destroy');
});