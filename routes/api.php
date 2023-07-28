<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/categories', [NewsController::class, 'getCategories']);

Route::middleware(['guest'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'authenticate']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
