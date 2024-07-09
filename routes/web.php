<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarCodeMakerController;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get("/menu", function(){
    return view('menu');
});

Route::middleware(['auth','check_pending'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('get-products', [WooCommerceController::class, 'getProducts'])->name('get.products');
# Admin Routes
   

});
Route::get('admin', [AdminController::class,'index'])->name('admin.index');
Route::put('admin',[AdminController::class, 'acceptUsers'])->name('admin.accept');;
Route::delete('admin/{id}',[AdminController::class, 'deleteUsers'])->name('admin.delete');


# Sell routes
Route::get('sell-product',[WooCommerceController::class,'sellProducts'])->name('products.sell');
Route::put('update-product',[WooCommerceController::class,'updateProducts'])->name('products.update');
Route::get('generate-bar-code',[BarCodeMakerController::class,"testing"])->name('barcode.get');




require __DIR__.'/auth.php';
