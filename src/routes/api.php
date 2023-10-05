<?php

use App\Http\Controllers\EcommerceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [EcommerceController::class, 'login']);
Route::post('/register', [EcommerceController::class, 'register']);
Route::post('/ecommerce/products/list', [EcommerceController::class, 'productList']);

//APIS PRIVADAS
Route::group(['middleware' => ['token.check']], function () {
    Route::post('/logout', [EcommerceController::class, 'logout']);
    Route::post('/shoppingcart/update', [EcommerceController::class, 'updateShoppingCartDataBase']);
    Route::post('/shoppingcart/buy', [EcommerceController::class, 'buyCartList']);
    Route::post('/shoppingcart/history', [EcommerceController::class, 'purchaseHistory']);
    Route::post('/shoppingcart/purchase/details', [EcommerceController::class, 'purchaseDetails']);
});
