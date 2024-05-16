<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\customerAddress;
use App\Models\BuyingAssistanceProducts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class formController extends Controller
{
    //Save data from shopify store form
    public function customer_store_data(Request $request)
    {
        try {

            // Start a new database transaction
            DB::beginTransaction();

            // Get data from the form submission
            $data = $request->all();

            // Create a new customer and save it to the database
            $customer = new Customer;
            $customer->customer_id = $data['CUSTOMER_ID'];
            $customer->first_name = $data['first_name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->phone = $data['CONTACT']; // Assuming 'CONTACT' is the phone number
            $customer->save(); // Save the data

            // Create a new customer address and save it to the database
            $address = new CustomerAddress;
            $address->street = $data['STREET'];
            $address->region = $data['REGION'];
            $address->province = $data['PROVINCE'];
            $address->barangay = $data['BARANGAY'];
            $address->city = $data['CITY'];
            $address->zipcode = $data['ZIPCODE'];
            $address->birthdate = $data['BIRTHDATE'];
            $address->gender = $data['GENDER'];
            $address->contact = $data['CONTACT'];
            $address->hear = $data['HEAR'];
            $address->customer_id = $customer->id; // Associate the address with the customer  
            $address->save(); // Save the data

            // Commit the transaction if no error.
            DB::commit();

            // Return the data as a response
            return response()->json($data);
            
        } catch (\Exception $e) {
            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving customer data: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return response()->json(['error' => 'An error occurred while saving the data. Please try again.'], 500);
        }
    }

     // Pass data from database to fill shopify store form
     public function customer_pass_data(Request $request, $customer_id){

             // Get data from the form submission
             $customer = Customer::where('customer_id', $customer_id)->first();
             if ($customer === null) {
                 return response()->json(['error' => 'No data found for the given customer id: ' . $customer_id], 404);
             }
 
             $customer_address = CustomerAddress::where('customer_id', $customer->id)->first();
             if ($customer_address === null) {
                 return response()->json([
                    'error' => 'No address found for the given address id: ' . $customer->id,
                    'customer_address' => $customer_address
                
                ], 404);
             }
 
             // Combine customer and address data
             $data = array_merge($customer->toArray(), $customer_address->toArray());
 
             // Return the data as a response
             return response()->json($data);

     }



    //Update data from shopify store form
    public function customer_store_data_update(Request $request, $customer_id)
    {
        try {
            // Start a new database transaction
            DB::beginTransaction();
    
            // Get data from the form submission
            $data = $request->all();
    
            // Fetch the existing customer and update it
            $customer = Customer::where('customer_id', $customer_id)->first();
            $customer->first_name = $data['first_name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->phone = $data['CONTACT']; // Assuming 'CONTACT' is the phone number
            $customer->update(); // Update the data
    
            // Fetch the existing address and update it
            $address = CustomerAddress::where('customer_id', $customer->id)->first();
            $address->street = $data['STREET'];
            $address->region = $data['REGION'];
            $address->province = $data['PROVINCE'];
            $address->barangay = $data['BARANGAY'];
            $address->city = $data['CITY'];
            $address->zipcode = $data['ZIPCODE'];
            $address->birthdate = $data['BIRTHDATE'];
            $address->gender = $data['GENDER'];
            $address->contact = $data['CONTACT'];
            $address->hear = $data['HEAR'];
            $address->update(); // Update the data
    
            // Commit the transaction if no error.
            DB::commit();
    
            // Return the data as a response
            return response()->json($data);
            
        } catch (\Exception $e) {
            // An error occurred. Rollback the transaction.
            DB::rollBack();
    
            // Log the error
            Log::error('Error updating customer data: ' . $e->getMessage());
    
            // Return a response indicating an error occurred
            return response()->json(['error' => 'An error occurred while updating the data. Please try again.'], 500);
        }
    }
    








    //Get and save products from Buying Assistance page to database - californila shopify
     public function get_buying_assistance_products(Request $request){

        $customer = null; // Declare $customer before the try block
    
        try {
    
            // Start a new database transaction
            DB::beginTransaction();
    
            // Get data from the form submission
            $data = $request->all();
        
            // Find the customer in the database
            $customer = Customer::where('customer_id', $data['customer_id'])->first();
        
            // Check if a customer was found
            if (!$customer) {
                // Handle the error, e.g., return a response with an error message
                return response()->json(['error' => 'Customer not found'], 404);
            }
        
            // Split the product data into arrays
            $product_names = explode(',', $data['product_name']);
            $product_links = explode(',', $data['product_link']);
            $product_qtys = explode(',', $data['product_qty']);
            $product_variants = explode(',', $data['product_variant']);
    
            // Loop through the products and save each one to the database
            for ($i = 0; $i < count($product_names); $i++) {
                $BuyingAssistanceProducts = new BuyingAssistanceProducts;
                $BuyingAssistanceProducts->product_name = $product_names[$i];
                $BuyingAssistanceProducts->product_link = $product_links[$i];
                $BuyingAssistanceProducts->product_qty = $product_qtys[$i];
                $BuyingAssistanceProducts->product_variant = $product_variants[$i];
                $BuyingAssistanceProducts->shipping_method = $data['shipping_method'];
                $BuyingAssistanceProducts->request_method = $data['method'];
                $BuyingAssistanceProducts->customer_id = $customer->id; // Associate the BuyingAssistanceProducts with the customer  
                $BuyingAssistanceProducts->save(); // Save the data
            }
    
            // Commit the transaction if no error.
            DB::commit();
    
            // Return the data as a response
            return response()->json($data);
    
        } catch (\Exception $e) {
            // Log the error message along with the customer_id for traceability
            Log::error('Error in get_buying_assistance_products for customer_id ' . ($customer ? $customer->id : 'unknown') . ': ' . $e->getMessage());
    
            // Log the request data and the exception stack trace for debugging
            Log::error('Request data: ' . print_r($data, true));
            Log::error('Exception stack trace: ' . $e->getTraceAsString());
    
            // Return a response indicating an error occurred
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    
    }
    
    

 
 
    
}
