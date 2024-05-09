<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddedProduct;
use PHPShopify\ShopifySDK;
use Illuminate\Support\Facades\Storage;

class CustomersController extends Controller
{

    public function getCustomers(){ 
 
        $shopify = new ShopifySDK;
        $customers = $shopify->Customer->get();  // Get all customers   
        foreach ($customers as $customerData) {
            $customer = Customer::updateOrCreate(
                ['email' => $customerData['email']],
                [
                    'customer_id' => $customerData['id'],
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'], 
                    'phone' => $customerData['phone'], 
                ]
            );
        }        
    
        $customers = Customer::all(); // Get all customers from the database
    
        return view('customers.customers', ['customers' => $customers]);
    
    }
    

    // Customer display data profile
    public function openCustomersDataProfile($id){ 
        $customerDataProfile = Customer::find($id);  
        $customerAddedProductData = CustomerAddedProduct::where('customer_id', $id)->get(); 
        return view('customers.customerDataProfile', 
        [
            'customerDataProfile' => $customerDataProfile,
            'customerAddedProductData' => $customerAddedProductData
        ]);
    }

    // Customer consolidate
    public function customerConsolidate($id){
        $shopify = new ShopifySDK;
        $customerConsolidateDataProfile = $shopify->Customer($id)->get();    
        return view('customers.customerConsolidate', ['customerConsolidateDataProfile' => $customerConsolidateDataProfile]);
    }

    // Customer add product page
    public function customerAddProducts($id){  
        $customer_id = $id; 
        return view('customers.customerAddProducts', ['customer_id' => $customer_id]);
    }

    // Customer Add/Store product
    public function customerAddProductsStore(Request $request)
    {
        try {
            $data = $request->validate([
                'customer_id' => 'required|integer',
                'warehouse_status' => 'required|string',
                'quantity' => 'required|numeric',
                'order_number' => 'required|string',
                'merchant' => 'required|string',
                'package_type' => 'required|string',
                'value' => 'required|numeric',
                'description' => 'required|string',
                'status' => 'required|string', 
                'note' => 'required|string', 
                'product_image' => 'required|image', 
            ]);
    
            // Handle the image upload
            if($request->hasFile('product_image')){
                $image = $request->file('product_image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = 'images/' . $filename;
                Storage::disk('public')->put($location, file_get_contents($image));
                $data['product_image'] = $location;
            }
    
            $product = new CustomerAddedProduct($data);
            $product->save();
    
            return redirect()->route('openCustomersDataProfile', ['id' => $request->customer_id])->with('success', 'Product saved successfully');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->route('openCustomersDataProfile', ['id' => $request->customer_id])->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    //EDIT CUSTOMER PRODUCT
    public function customerEditProduct($id, $product_id){
        $customer_id = $id;
        $customer_product_id = $product_id;
        $customerProduct = CustomerAddedProduct::find($product_id);
        return view('customers.customerEditProduct', 
        [
            'customer_product_id' => $customer_product_id,
            'customer_id' => $customer_id,
            'customerProduct' => $customerProduct
        ]);
    }

    //UPDATE CUSTOMER PRODUCT
    public function customerUpdateProduct(Request $request, $id, $product_id){  
        try {
            $data = $request->validate([
                'customer_id' => 'required|integer',
                'warehouse_status' => 'required|string',
                'quantity' => 'required|numeric',
                'order_number' => 'required|string',
                'merchant' => 'required|string',
                'package_type' => 'required|string',
                'value' => 'required|numeric',
                'description' => 'required|string',
                'status' => 'required|string', 
                'note' => 'required|string', 
                'product_image' => 'nullable|image', 
            ]);

            // Handle the image upload
            if($request->hasFile('product_image')){
                $image = $request->file('product_image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = 'images/' . $filename;
                Storage::disk('public')->put($location, file_get_contents($image));
                $data['product_image'] = $location;
            }

            // Retrieve the product from the database
            $product = CustomerAddedProduct::find($product_id);

            // Update the product's properties
            $product->update($data);

            return redirect()->route('openCustomersDataProfile', ['id' => $request->customer_id])->with('success', 'Product Updated successfully');
        } catch (\Exception $e) {
            // Handle the exception
            // You can log the exception message or display it to the user
            return back()->withErrors(['error' => 'There was an error updating the product: ' . $e->getMessage()]);
        }
    }

    //DELETE CUSTOMER PRODUCT
    public function customerDeleteProduct($id, $product_id){
        // Retrieve the product from the database
        $product = CustomerAddedProduct::find($product_id);
        $product->delete();

        return redirect()->back()->with('success', 'Product Deleted successfully');
    }


    
} 