<?php

use App\Http\Controllers\FacultyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([Authenticate::class])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/current_user', 'getCurrentUser');
        Route::post('/update_current_user', 'updateCurrentUser');
    });

Route::controller(UniversityController::class)->group(function () {
    Route::get('/universities', 'index');
    Route::get('/university/{university_id}', 'show');
});

Route::controller(FacultyController::class)->group(function () {
    Route::get('/faculties', 'index');
    Route::get('/faculty/{faculty_id}', 'show');
});

Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'index');
    Route::get('/role/{role_id}', 'show');
});
