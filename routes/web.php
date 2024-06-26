<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;



Route::get('get-products', [WooCommerceController::class, 'getProducts'])->name('get.products');

# Sell routes
Route::get('sell-product/{id}',[WooCommerceController::class,'sellProducts'])->name('sell.products');
Route::put('update-product',[WooCommerceController::class,'updateProducts'])->name('update.products');

# Register Routes
Route::get('register',[RegisterController::class, "index"])->name('register');
Route::post('register',[RegisterController::class, "registerUser"])->name('register');


# Login Routes

Route::get('login',[LoginController::class,"index"])->name('login');