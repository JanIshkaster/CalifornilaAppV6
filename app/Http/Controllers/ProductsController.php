<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class ProductsController extends Controller
{
    public function getProducts(){

        $shopify = new ShopifySDK;
        $products = $shopify->Product->get(); 
        return view('products', ['products' => $products]);

    }
}
