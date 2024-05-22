<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\customerAddress;
use App\Models\DeclaredProducts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ticketController extends Controller
{
    //Ticket Page
    public function ticket_index() {
        
        // Retrieve only customers who have DeclaredProducts
        $customers = Customer::with('DeclaredProducts')->has('DeclaredProducts')->get();
    
        return view('tickets.ticket_index', ['customers' => $customers]);
    }
    
    

    //Assign Ticket Page
    public function assign_ticket($customer_id){

        try {

            // Start a new database transaction
            DB::beginTransaction();

            // Check if a ticket with the given customer_id already exists
            $existing_ticket = Ticket::where('customer_id', $customer_id)->first();

            //Get customer details with products and ticket
            $customer = Customer::with(
                [
                    'DeclaredProducts', 
                    'Ticket.ticketAdditionalFees', 
                    'Ticket.ticketNotes'
                ])->find($customer_id); 
        
            if ($existing_ticket) {
                // If a ticket already exists, return the view with the existing ticket_id
                return view('tickets.assign-ticket', [
                    'customer' => $customer, 
                    'ticket_id' => $existing_ticket->ticket_id,
                    'notes' => $existing_ticket->ticketNotes
                ]);
            }

        
            // Generate a ticket_id with a date prefix
            $date_prefix = date('Ymd'); // This will give a string like "20240522" for May 22, 2024
            $generate_ticket_id = $date_prefix . '_' . Str::random(5); // This will append a random 5-character string to the date prefix
        
            // Save generated ticket ID
            $ticket = new Ticket;
            $ticket->customer_id = $customer->id;
            $ticket->ticket_id = $generate_ticket_id;
            $ticket->save();

            // Commit the transaction if no error.
            DB::commit();
        
            return view('tickets.assign-ticket', ['customer' => $customer, 'ticket_id' => $ticket->ticket_id, 'uniqueShippingMethods' => $uniqueShippingMethods]);
            
        } catch (\Throwable $e) {

            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving ticket id: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return back()->with(['error' => 'Error Saving Ticket ID']);

        } 
    }



    //Add products - Ticket Page
    public function addProducts(Request $request, $customer_id){ 

       try {
        
            // Start a database transaction
            DB::beginTransaction();

            // Validate the request
            $request->validate([
                'product_name' => 'required|string|max:255',
                'product_link' => 'required|url',
                'product_qty' => 'required|integer|min:1',
                'product_variant' => 'nullable|string|max:255', 
                'shipping_method' => 'nullable|string|max:255', 
                'request_method' => 'nullable|string|max:255', 
            ]);

            // Get validated data
            $data = $request->all();

            // Create a new product and save it to the database
            $addProduct = new DeclaredProducts;
            $addProduct->customer_id = $customer_id; 
            $addProduct->product_name = $data['product_name'];  
            $addProduct->product_link = $data['product_link']; 
            $addProduct->product_qty = $data['product_qty'];  
            $addProduct->product_variant = $data['product_variant'];  
            $addProduct->shipping_method = $data['shipping_method'];  
            $addProduct->request_method = $data['request_method'];  
            $addProduct->save(); // Save the data

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Product added successfully'); // Redirect back with a success message

       } catch (\Throwable $e) {
        
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Failed to add product: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to add product. Please try again.');
        }

    }


    //Delete products - Ticket Page
    public function deleteProducts($customer_id, $product_id){ 
 
        // Find the product by ID
        $product = DeclaredProducts::findOrFail($product_id);

        // Delete the product
        $product->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product deleted successfully.'); 
 
     }
    
}
