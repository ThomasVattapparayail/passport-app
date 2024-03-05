<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::post('register', [LoginController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', function (Request $request) { return $request->user();});

    
    Route::get('/products', [CustomerController::class, 'productList']);
    Route::get('admin/users', [AdminController::class, 'viewAllUsers']);
    Route::put('admin/user_edit/{user}', [AdminController::class, 'userUpdate']);
    Route::delete('admin/user_delete/{user}', [AdminController::class, 'userDestroy']);

    Route::post('admin/products_add', [AdminController::class, 'addProduct']);
    Route::put('admin/products_edit/{product}', [AdminController::class, 'editProduct']);
    Route::delete('admin/products_delete/{product}', [AdminController::class, 'deleteProduct']);

    Route::post('admin/stocks_add', [AdminController::class, 'addStock']);
    Route::put('admin/stocks_edit/{stock}', [AdminController::class, 'editStock']);
    Route::delete('admin/stocks_delete/{stock}', [AdminController::class, 'deleteStock']);

    Route::get('/logout', [LoginController::class, 'logout']);

});

    
