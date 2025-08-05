<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/movies/favorite', [MovieController::class, 'addToFavorites']);
    Route::get('/movies/favorites', [MovieController::class, 'listFavorites']);
    Route::delete('/movies/favorite/{movie_id}', [MovieController::class, 'removeFromFavorites']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('movies', MovieController::class);
});



