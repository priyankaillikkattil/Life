<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController; 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/login',    [AuthController::class, 'index'])->name('login');
Route::middleware('auth:sanctum','throttle:50,1')->group(function (){ 
    
    Route::post('/products',        [ProductController::class, 'store']);
    Route::get('/products/{id}',    [ProductController::class, 'show']);
    Route::put('/products/{id}',    [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/user',     [AuthController::class, 'userProfile']);    

    Route::post('/categories',          [CategoryController::class, 'store']);   
    Route::put('/categories/{id}',      [CategoryController::class, 'update']);
    Route::delete('/categories/{id}',   [CategoryController::class, 'destroy']);
       
});
// Authenticated Users (Customers, Admin & Super Admin)
Route::middleware('auth:sanctum')->group(function () { 
    Route::get('/products',         [ProductController::class, 'index']);
    Route::get('/products/{id}',    [ProductController::class, 'show']);

    Route::get('/categories',       [CategoryController::class, 'index']);
    Route::get('/categories/{id}',  [CategoryController::class, 'show']);

    Route::post('/orders',          [OrderController::class, 'store']);   
    Route::get('/orders',           [OrderController::class, 'index']);    
    Route::get('/orders/{id}',      [OrderController::class, 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
