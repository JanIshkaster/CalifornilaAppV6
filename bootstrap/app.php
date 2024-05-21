<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Cors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [ 
            'customer_store_data', // <-- exclude this route for customer profile
            'customer_pass_data/*',// <-- exclude this route for customer profile
            'customer_pass_data',// <-- exclude this route for customer profile
            'customer_store_data_update',// <-- exclude this route for customer update profile
            'customer_store_data_update/*',// <-- exclude this route for customer update profile
            'get_declared_products' // <-- exclude this route for buying assistance
        ]); 
        $middleware->append(Cors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
