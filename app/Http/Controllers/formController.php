<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\customerAddress;
use Illuminate\Support\Facades\Log;

class formController extends Controller
{
    //Handle data from shopify store form
    public function customer_store_data(Request $request)
    {
        try {
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
            $address->zipcode = $data['ZIPCODE'];
            $address->birthdate = $data['BIRTHDATE'];
            $address->gender = $data['GENDER'];
            $address->contact = $data['CONTACT'];
            $address->hear = $data['HEAR'];
            $address->customer_id = $customer->id; // Associate the address with the customer  
            $address->save(); // Save the data

            // Return the data as a response
            return response()->json($data);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error saving customer data: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return response()->json(['error' => 'An error occurred while saving the data. Please try again.'], 500);
        }
    }
}
