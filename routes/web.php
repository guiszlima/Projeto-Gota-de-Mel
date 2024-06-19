<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\WooCommerceController;

Route::get('/get-products', [WooCommerceController::class, 'getProducts'])->name('get.products');
Route::get('sell-product/{id}',[WooCommerceController::class,'sellProducts'])->name('sell.products');
Route::put('update-product',[WooCommerceController::class,'updateProducts'])->name('update.products');