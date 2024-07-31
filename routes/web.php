<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarCodeMakerController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','check_pending'])->group(function () {

    Route::get("/menu", function(){
        $user = Auth::user();
    
    return view('menu')->with('user', $user);
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('get-products', [WooCommerceController::class, 'getProducts'])->name('get.products');
    
   

});
# Admin Routes


Route::get('admin', [AdminController::class,'index'])->name('admin.index');
Route::put('admin/{id}', [AdminController::class,'update'])->name('admin.update');
Route::delete('admin/{id}',[AdminController::class, 'deleteUsers'])->name('admin.delete');


# Accept users to get in system routes
Route::get('admin-accept', [AdminController::class,'accept_index'])->name('admin.index.accept');
Route::put('admin-accept',[AdminController::class, 'acceptUsers'])->name('admin.accept');;




# Sell routes
Route::get('sell-product',[WooCommerceController::class,'sellProducts'])->name('products.sell');
Route::post('sell-product',[WooCommerceController::class,'makeSell'])->name('products.make-sell');
Route::post('payment',[WooCommerceController::class,'payment'])->name('products.payment');

# BarCode Generator
Route::get('barcode',[BarCodeMakerController::class,'index'])->name('barcode.index');
Route::get('gerar-codigo',[BarCodeMakerController::class,'generate'])->name('barcode.generate');

require __DIR__.'/auth.php';