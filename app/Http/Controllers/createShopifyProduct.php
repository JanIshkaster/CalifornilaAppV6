<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class createShopifyProduct extends Controller
{
    //Create shopify product - sample
    public function create_shopify_product() {

        $shopify = new ShopifySDK;
        $product = $shopify->Product->post([
            "title" => "Test Product",
            "body_html" => "<strong>Awesome Product!</strong>",
            "vendor" => "Your Vendor",
            "product_type" => "approved-products",
            "images" => [
                ["src" => "https://server.californila.com/public/material/assets/images/logo-light-text.png"]
            ],
            "variants" => [
                [
                    "price" => "1.00"
                ]
            ]
        ]);
        
    }
}
