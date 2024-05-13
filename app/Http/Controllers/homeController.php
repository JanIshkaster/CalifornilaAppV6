<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class homeController extends Controller
{
    public function home(){

        $shopify = new ShopifySDK;
        $customers_count = $shopify->Customer->count();  // Get all customers from store 
        $orders_count = $shopify->Order->count();  // Get all orders from store 
        $products_count = $shopify->Product->count();  // Get all products from store 
        return view('index', ['customers_count' => $customers_count, 'orders_count' => $orders_count, 'products_count' => $products_count]); 
    }
}
