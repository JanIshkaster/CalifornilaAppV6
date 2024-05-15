<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\customerAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class formController extends Controller
{
    //Handle data from shopify store form
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
     public function customer_pass_data(Request $request, $CUSTOMER_ID)
     {
         try {
 
             // Get data from the form submission
             $customer = Customer::where('customer_id', $CUSTOMER_ID)->first();
             if ($customer === null) {
                 return response()->json(['error' => 'No data found for the given CUSTOMER_ID'], 404);
             }
 
             $customer_address = CustomerAddress::find($customer->id);
             if ($customer_address === null) {
                 return response()->json(['error' => 'No address found for the given CUSTOMER_ID'], 404);
             }
 
             // Combine customer and address data
             $data = array_merge($customer->toArray(), $customer_address->toArray());
 
             // Return the data as a response
             return response()->json($data);
 
         } catch (\Exception $e) {

             // Log the error message along with the CUSTOMER_ID for traceability
             Log::error('Error in customer_pass_data for CUSTOMER_ID ' . $CUSTOMER_ID . ': ' . $e->getMessage());
 
             // Return a response indicating an error occurred
             return response()->json(['error' => 'An error occurred while processing your request'], 500);
         }
     }

 
 
    
}
