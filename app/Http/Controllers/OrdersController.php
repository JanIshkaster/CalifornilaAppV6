<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class OrdersController extends Controller
{
    public function getOrders(){

        $shopify = new ShopifySDK;
        $orders = $shopify->Order->get();  
        return view('orders', ['orders' => $orders]);

    }
}
