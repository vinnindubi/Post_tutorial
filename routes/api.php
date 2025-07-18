<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', AuthController::class);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('/posts',PostController::class);
    Route::apiResource('/posts/{post}/comments', CommentController::class);
});