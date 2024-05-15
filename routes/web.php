<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\formController;
use App\Http\Controllers\createShopifyProduct;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Http\Middleware\Cors;
use App\Http\Middleware\VerifyCsrfToken;
use PHPShopify\ShopifySDK;

// Route::get('/', function () {
//     return view('index')->name('home');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //HOMEPAGE PAGE
    Route::get('/', [homeController::class, 'home'])->name('home');
    
    //PRODUCTS PAGE
    Route::get('/products', [ProductsController::class, 'getProducts'])->name('getProducts');
    
    //Customers PAGE
    Route::get('/customers', [CustomersController::class, 'getCustomers'])->name('getCustomers'); //DISPLAY CUSTOMERS
    Route::get('/customers/{id}', [CustomersController::class, 'openCustomersDataProfile'])->name('openCustomersDataProfile');  //DISPLAY CUSTOMERS PROFIL
    Route::get('/customers/{id}/add_product', [CustomersController::class, 'customerAddProducts'])->name('customerAddProducts');  //DISPLAY CUSTOMER ADD PRODUCT
    Route::post('/customers/{id}/add_product', [CustomersController::class, 'customerAddProductsStore'])->name('customerAddProductsStore');  //STORE CUSTOMER PRODUCT
    Route::get('/customers/{id}/edit_product/{product_id}', [CustomersController::class, 'customerEditProduct'])->name('customerEditProduct');  //EDIT CUSTOMER PRODUCT PAGE
    Route::put('/customers/{id}/update_product/{product_id}', [CustomersController::class, 'customerUpdateProduct'])->name('customerUpdateProduct');  //UPDATE CUSTOMER PRODUCT PAGE
    Route::delete('/customers/{id}/delete_product/{product_id}', [CustomersController::class, 'customerDeleteProduct'])->name('customerDeleteProduct');  //DELETE CUSTOMER PRODUCT PAGE
    Route::get('/customers/{id}/consolidate', [CustomersController::class, 'customerConsolidate'])->name('customerConsolidate');    //DISPLAY CUSTOMERS CONSOLIDATE
        
    //PRODUCTS PAGE
    Route::get('/orders', [OrdersController::class, 'getOrders'])->name('getOrders');

    //CALCULATOR PAGE
    Route::get('/calculator', [CalculatorController::class, 'calculatorView'])->name('calculatorView'); //CALCULATOR DISPLAY
    Route::post('/calculator', [CalculatorController::class, 'calculator'])->name('calculator'); //CALCULATOR COMPUTATIONS | RESULT DISPLAY
    
    //SETTINGS PAGE
    Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'settings_form'])->name('settings_form');

    //CREATE PRODUCTS IN SHOPIFY STORE
    Route::get('/create_shopify_product', [createShopifyProduct::class, 'create_shopify_product'])->name('create_shopify_product');
 

});

    //Handle form data from Shopify -> Customers data
    Route::post('/customer_store_data', [formController::class, 'customer_store_data']);

    //Pass data from database to fill shopify store form
    Route::post('/customer_pass_data/{CUSTOMER_ID}', [formController::class, 'customer_pass_data']);
 

require __DIR__.'/auth.php';
