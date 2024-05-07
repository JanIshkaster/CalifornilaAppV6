<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\homeController;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;
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
    Route::get('/customers/{id}', [CustomersController::class, 'openCustomersDataProfile'])->name('openCustomersDataProfile');//DISPLAY CUSTOMERS PROFILE

    //PRODUCTS PAGE
    Route::get('/orders', [OrdersController::class, 'getOrders'])->name('getOrders');

    //CALCULATOR PAGE
    Route::get('/calculator', [CalculatorController::class, 'calculatorView'])->name('calculatorView'); //CALCULATOR DISPLAY
    Route::post('/calculator', [CalculatorController::class, 'calculator'])->name('calculator'); //CALCULATOR COMPUTATIONS | RESULT DISPLAY
    
    //SETTINGS PAGE
    Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'settings_form'])->name('settings_form');

});

require __DIR__.'/auth.php';
