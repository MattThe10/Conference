<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UniversityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'index');
    Route::get('/role/{id}', 'show');
});

Route::controller(UniversityController::class)->group(function () {
    Route::get('/universities', 'index');
    Route::get('/university/{id}', 'show');
});

Route::controller(FacultyController::class)->group(function () {
    Route::get('/faculties', 'index');
    Route::get('/faculty/{id}', 'show');
});

Route::controller(CountryController::class)->group(function () {
    Route::get('/countries', 'index');
    Route::get('/country/{id}', 'show');
});