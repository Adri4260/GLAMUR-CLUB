<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/reviews/{product_id}', [ReviewController::class, 'index']);


Route::middleware('auth:sanctum')->post('/reviews', [ReviewController::class, 'store']);
