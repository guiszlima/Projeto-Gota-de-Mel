<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\WooCommerceController;

Route::get('/get-products', [WooCommerceController::class, 'getProducts']);
