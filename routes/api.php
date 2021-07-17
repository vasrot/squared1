<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth
Route::post('/register', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// User
Route::apiResource('user', UserController::class);
Route::get('/user/{user}/posts', [UserController::class, 'posts']);

// Posts
Route::apiResource('post', PostController::class);

// Import external posts
Route::get('/import', [PostController::class, 'import']);
