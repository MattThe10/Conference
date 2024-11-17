<?php

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
