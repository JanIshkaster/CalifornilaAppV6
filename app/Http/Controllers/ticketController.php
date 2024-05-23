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

        $customers = Customer::with(['DeclaredProducts', 'Ticket'])->has('DeclaredProducts')->get();
    
        // Initialize an empty array to hold the customers and their tickets
        $customer_tickets = [];
    
        foreach ($customers as $customer) {
            // For each shipping method, add the customer and their ticket to the array
            foreach ($customer->DeclaredProducts->groupBy('shipping_method') as $shipping_method => $products) { 
                $ticket = $customer->Ticket->where('shipping_method', $shipping_method)->first();
                $ticket_id = $ticket ? $ticket->ticket_id : null;

                $customer_tickets[] = [
                    'customer' => $customer,
                    'ticket_id' => $ticket_id, 
                    'shipping_method' => $shipping_method
                ];
            }
        }
    
        return view('tickets.ticket_index', ['customer_tickets' => $customer_tickets, 'customers' => $customers]);
    }
    

    
     

    //Assign Ticket Page
    public function assign_ticket(Request $request, $customer_id, $ticket_id){ 

        $data = $request->all();
        $shipping_method = $data['shipping_method'];

        try {

            // Start a new database transaction
            DB::beginTransaction();

            // Check if a ticket with the given customer_id already exists
            $existing_ticket = Ticket::where('customer_id', $customer_id)->first();

            //Get customer details with products and ticket
            $customer = Customer::with(
                ['DeclaredProducts', 
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

        
            if ($ticket_id == 'assign_ticket_number') {
                // Generate a ticket ID
                $date_prefix = date('Ymd'); // This will give a string like "20240522" for May 22, 2024
                $ticket_number = Ticket::where('customer_id', $customer_id)->count() + 1; // Get the count of existing tickets for the specific customer and add one to it
                $formatted_ticket_number = str_pad($ticket_number, 5, '0', STR_PAD_LEFT); // Format the ticket number to have 5 digits with leading zeros
                $ticket_id = $date_prefix . '_' . $formatted_ticket_number; // This will append the formatted ticket number to the date prefix
            }
        
            // Save generated ticket ID
            $ticket = new Ticket;
            $ticket->customer_id = $customer->id;
            $ticket->ticket_id = $ticket_id;
            $ticket->shipping_method = $shipping_method;
            $ticket->save();

            // Commit the transaction if no error.
            DB::commit();
        
            return redirect()->route('view_ticket')->with('success', 'Ticket assigned successfully');
            
        } catch (\Throwable $e) {

            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving ticket id: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return back()->with(['error' => 'Error Saving Ticket ID']);

        } 
    } 

    //Open Ticket Page
    public function view_ticket(Request $request, $customer_id, $ticket_id){ 

        // Check if a ticket with the given customer_id already exists
        $existing_ticket = Ticket::where('customer_id', $customer_id)->first();

        //Get customer details with products and ticket
        $customer = Customer::with(
            ['DeclaredProducts', 
                'Ticket.ticketAdditionalFees', 
                'Ticket.ticketNotes'
            ])->find($customer_id); 
    
        if ($existing_ticket) {
            // If a ticket already exists, return the view with the existing ticket_id
            return view('tickets.view-ticket', [
                'customer' => $customer, 
                'ticket_id' => $existing_ticket->ticket_id,
                'notes' => $existing_ticket->ticketNotes
            ]);
        }
    
        return view('tickets.view-ticket', ['customer' => $customer, 'ticket_id' => $ticket_id]);
     
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
