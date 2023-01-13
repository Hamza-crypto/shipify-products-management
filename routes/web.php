<?php

use App\Http\Controllers\DatatableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', '/products');

Route::group(['middleware' => 'auth'], function () {
    Route::get('products', [ProductController::class, 'index'])->name('product.index');
    Route::get('products/sku', [ProductController::class, 'sku'])->name('product.sku');
    Route::post('products/sku', [ProductController::class, 'sku_update_from_csv'])->name('product.sku_update');
    Route::post('products/delete', [ProductController::class, 'delete_products'])->name('product.delete');

    Route::get('api/v1/products', [DatatableController::class, 'products'])->name('products.ajax');
});



Route::resource('users', UsersController::class);
Route::get('users/export', [UsersController::class, 'export_users'])->name('users.export');
Route::get('products/export', [ProductController::class, 'exports'])->name('products.export');




