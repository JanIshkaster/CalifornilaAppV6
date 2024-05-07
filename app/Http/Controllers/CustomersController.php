<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;

class CustomersController extends Controller
{
    public function getCustomers(){

        $shopify = new ShopifySDK;
        $customers = $shopify->Customer->get();  
        return view('customers.customers', ['customers' => $customers]);

    }

    // Controller action
    public function openCustomersDataProfile($id){
        $shopify = new ShopifySDK;
        $customerDataProfile = $shopify->Customer($id)->get();    
        return view('customers.customerDataProfile', ['customerDataProfile' => $customerDataProfile]);
    }
} 