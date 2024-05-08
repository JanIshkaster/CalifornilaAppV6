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

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $config = array(
            'ShopUrl' => env('SHOPIFY_SHOP_URL', 'californila-v2.myshopify.com'),
            'AccessToken' => env('SHOPIFY_ADMIN_ACCESS_TOKEN', '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'),
        );
        ShopifySDK::config($config);
    }
}
