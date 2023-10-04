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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
