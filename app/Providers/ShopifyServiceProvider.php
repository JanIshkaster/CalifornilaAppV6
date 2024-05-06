<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPShopify\ShopifySDK;

class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $config = array(
            'ShopUrl' => env('SHOPIFY_SHOP_URL'),
            'AccessToken' => env('SHOPIFY_ADMIN_ACCESS_TOKEN'),
        );
        ShopifySDK::config($config);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
