<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationAPIController;

                    // A U T H E N T I C A T I O N
Route::post('login',[AuthenticationAPIController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{productId}', [ProductController::class, 'show']);
Route::resource('carts',CartController::class);
Route::resource('wishList',WishListController::class);
Route::get('user-wish-list',[WishListController::class,'userWishList']);
Route::get('user-cart',[CartController::class,'userCart']);
 
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('products', ProductController::class)
         ->except(['index', 'show']);
});