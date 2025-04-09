<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'handle']);
    Route::post('/login', [LoginController::class, 'handle']);
    Route::post('/logout', [LogoutController::class, 'handle'])->middleware('auth:sanctum');
});

Route::prefix('category')->group(function () {
    Route::post('/create', [CategoryController::class, 'createCategory']);
    Route::get('/', [CategoryController::class, 'index']);
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
});

Route::prefix('course')->group(function () {
    Route::post('/create', [CourseController::class, 'createCourse'])->middleware('auth:sanctum');
    Route::post('/{courseId}/videos', [CourseVideoController::class, 'create']);
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{id}', [CourseController::class, 'show']);
    Route::delete('/delete/{id}', [CourseController::class, 'destroy']);
});

Route::prefix('event')->group(function () {
    Route::post('/create', [EventController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::delete('/delete/{id}', [EventController::class, 'destroy']);
});
