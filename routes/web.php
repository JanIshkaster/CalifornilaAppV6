<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use PHPShopify\ShopifySDK;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/products', [ProductsController::class, 'getProducts']);