<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/user/login', [AuthController::class, 'login']);
Route::post('/application/create', [ApplicationController::class, 'create']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/user/logout', [AuthController::class, 'logout']);

    // Course routes
    Route::get('/course/viewAll', [CourseController::class, 'viewAll']);
    Route::get('/course/{course_id}/view', [CourseController::class, 'view']);
    Route::post('/course/create', [CourseController::class, 'create']);
    Route::post('/course/update', [CourseController::class, 'update']);
    Route::post('/course/{course_id}/delete', [CourseController::class, 'delete']);

    // Post routes
    Route::get('/post/viewAll', [PostController::class, 'viewAll']);
    Route::get('/post/{post_id}/view', [PostController::class, 'view']);
    Route::post('/post/create', [PostController::class, 'create']);
    Route::post('/post/update', [PostController::class, 'update']);
    Route::post('/post/{post_id}/delete', [PostController::class, 'delete']);

    // Application routes
    Route::get('/application/viewAll', [ApplicationController::class, 'viewAll']);
    Route::post('/application/{application_id}/delete', [ApplicationController::class, 'delete']);
});

// Legacy routes for backward compatibility
Route::get('/courses', [CourseController::class, 'showAll']);
Route::get('/posts', [PostController::class, 'showAll']);
Route::post('/contacts', [SiteController::class, 'setContacts']);
Route::get('/contacts', [SiteController::class, 'getAll']);

