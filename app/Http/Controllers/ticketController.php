<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\customerAddress;
use App\Models\DeclaredProducts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\ticketAdditionalFees;
use App\Models\ticketPayments;
use App\Models\Settings;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class ticketController extends Controller
{
    //Ticket Page
    public function ticket_index() {
        $customers = Customer::with(['DeclaredProducts', 'Ticket'])->get();  
    
        // Initialize an empty array to hold the customers and their tickets
        $customer_tickets = [];
        
        foreach ($customers as $customer) {
            // Group declared products by both shipping_method and request_method
            $groupedProducts = $customer->DeclaredProducts->groupBy(['shipping_method', 'request_method']);
            
            foreach ($groupedProducts as $shipping_method => $requestMethods) {
                foreach ($requestMethods as $request_method => $products) {
                    // Find the ticket for the specific shipping method
                    $ticket = $customer->Ticket->where('shipping_method', $shipping_method)->first();  
                    $ticket_id = $ticket ? $ticket->ticket_id : null;   
                    $order_id = $ticket ? $ticket->order_id : null;  // Fetch order_id from the ticket
                    $status = $ticket ? $ticket->status : null;  // Fetch status from the ticket

                    $customer_tickets[] = [
                        'customer' => $customer,
                        'customer_shopify_id' => $customer->customer_id,
                        'ticket_id' => $ticket_id,  
                        'products' => $products,
                        'shipping_method' => $shipping_method,
                        'request_method' => $request_method,
                        'order_id' => $order_id,
                        'status' => $status,
                        'created_at' => $products->min('created_at') // Get the earliest creation date for sorting
                    ];
                }
            }
        } 
        
        // Sort the customer tickets by the created_at field
        usort($customer_tickets, function($a, $b) {
            return $a['created_at'] <=> $b['created_at'];
        });

        return view('tickets.ticket_index', ['customer_tickets' => $customer_tickets, 'customers' => $customers]);
    }
    
    

    
     

    //Assign Ticket Page
    public function assign_ticket(Request $request, $customer_id, $ticket_id){ 

        $data = $request->all();  
        $shipping_method = $data['shipping_method'];
        $order_id = $data['order_id'];
        $request_method = $data['request_method'];
        $productIds = $data['product_ids']; // Get the product IDs that are submitted as an array 

        try {

            // Start a new database transaction
            DB::beginTransaction();

            $existing_ticket = Ticket::with([
                'Customer',
                'DeclaredProducts',
                'ticketAdditionalFees',
                'ticketNotes'
            ])->where('ticket_id', $ticket_id)->first();  

            // Get customer details with products and ticket
            $customer = Customer::with([
                'DeclaredProducts',
                'Ticket.ticketAdditionalFees',
                'Ticket.ticketNotes'
            ])->find($customer_id);

            // Generate or update the ticket ID
            $ticket_id = $this->generateTicketId($existing_ticket, $customer_id);  

            // Save generated ticket ID
            $ticket = new Ticket;
            $ticket->customer_id = $customer_id;
            $ticket->order_id = $order_id;
            $ticket->ticket_id = $ticket_id;
            $ticket->request_method = $request_method;
            $ticket->shipping_method = $shipping_method;
            $ticket->save();


            // Attach product IDs to the ticket
            $ticket->DeclaredProducts()->attach($productIds); // many to many

            // Commit the transaction if no error.
            DB::commit();
        
            return redirect()->route('view_ticket', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id])->with('success', 'Ticket assigned successfully');
            
        } catch (\Throwable $e) {

            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving ticket id: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return back()->with(['error' => 'Error Saving Ticket ID']);

        } 
    } 


    //GENERATE TICKET ID
    private function generateTicketId($existing_ticket, $customer_id){
        $date_prefix = date('Ymd'); // This will give a string like "20240522" for May 22, 2024 

        if ($existing_ticket) {
            // If a ticket already exists, increment the ticket number
            $ticket_number = (int) substr($existing_ticket->ticket_id, -5); // Extract the numeric part of the existing ticket ID
            $new_ticket_number = $ticket_number + 1; // Increment the ticket number
        } else {
            // Generate a new ticket number
            $ticket_number = Ticket::where('customer_id', $customer_id)->count() + 1; // Get the count of existing tickets for the specific customer and add one to it
            $new_ticket_number = $ticket_number;
        }
    
        $formatted_ticket_number = str_pad($new_ticket_number, 5, '0', STR_PAD_LEFT); // Format the ticket number to have 5 digits with leading zeros
        $ticket_id = $date_prefix . '_' . $formatted_ticket_number; // Construct the updated ticket ID

        // If the ticket ID already exists, increment it by 1
        if (Ticket::where('ticket_id', $ticket_id)->exists()) {
            // Extract the numeric part of the existing ticket ID
            $ticket_number = (int) substr($ticket_id, -5);
            $new_ticket_number = $ticket_number + 1; // Increment the ticket number
            $formatted_ticket_number = str_pad($new_ticket_number, 5, '0', STR_PAD_LEFT); // Format the ticket number
            $ticket_id = $date_prefix . '_' . $formatted_ticket_number; // Construct the updated ticket ID
        }

        return $ticket_id;

    }





    //Open Ticket Page
    public function view_ticket($customer_id, $ticket_id) {
        // Check if a ticket with the given customer_id already exists
        $existing_ticket = Ticket::with([
            'Customer',
            'DeclaredProducts',
            'ticketAdditionalFees',
            'ticketNotes',
            'ticketPayments'
        ])->where('ticket_id', $ticket_id)->first();  

        // Collect all request_method values into an array
        $request_methods = $existing_ticket->DeclaredProducts->pluck('request_method')->all();
        $request_method = implode(',', $request_methods);  

        // Get the first ticket (if it exists) from the customer's tickets
        $firstTicket = $existing_ticket;
    
        // Retrieve all products associated with the specific ticket
        $products = $firstTicket ? $firstTicket->DeclaredProducts : collect(); // Use collect() to handle empty case 

        $admin_settings = Settings::first();  // Get admin settings
       
        if ($existing_ticket) {
            // If a ticket already exists, return the view with the existing ticket_id
            return view('tickets.view-ticket', [ 
                'ticket_id' => $ticket_id,
                'customer_id' => $customer_id,
                'firstTicket' => $firstTicket,
                'notes' => $existing_ticket->ticketNotes,
                'steps' => $existing_ticket->steps,
                'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                'existing_ticket' => $existing_ticket,
                'products' => $products,
                'status' => $existing_ticket->status,
                'request_method' => $request_method, 
                'admin_settings' => $admin_settings,
                'ticketPayments' => $existing_ticket->ticketPayments->first()
            ]);
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
            $addProduct->shipping_method = strtolower($data['shipping_method']);  
            $addProduct->request_method = $data['request_method'];  
            $addProduct->save(); // Save the data

            // Attach product IDs to the ticket
            $ticket = Ticket::where('ticket_id', $data['ticket_id'])->first(); // Get the ticket instance
            if($ticket) {
                $ticket->declaredProducts()->attach($addProduct->id); // Attach the product to the ticket
            }


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



    // Initial Payment - STEP 1
    public function initialPayment(Request $request) { 
        
        try {
            // Validate the request
            $validatedData = $request->validate([
                'ticket_id' => 'required|string|max:255',
                'steps' => 'required|integer|min:1',
                'totalCreditCardFee' => 'required',
                'totalHandlingFee' => 'required',
                'totalCustomTax' => 'required',
                'totalConvenienceFee' => 'required',
                'productTotalValue' => 'required',
                'productValue' => 'required',
                'product_qty' => 'nullable|integer|min:1',
                'status' => 'nullable|string|max:255',
                'payment_type' => 'nullable|string|max:255'
            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Create a new Ticket Payment data and save it to the database
            $ticketPayment = new TicketPayments($validatedData);
            $ticketPayment->ticket_id = $validatedData['ticket_id'];
            $ticketPayment->total_handling_fee = $validatedData['totalHandlingFee'];
            $ticketPayment->total_custom_tax = $validatedData['totalCustomTax'];
            $ticketPayment->total_convenience_fee = $validatedData['totalConvenienceFee'];
            $ticketPayment->total_credit_card_fee = $validatedData['totalCreditCardFee'];
            $ticketPayment->total_product_value = $validatedData['productValue'];
            $ticketPayment->total_product_price = $validatedData['productTotalValue'];
            $ticketPayment->payment_type = $validatedData['payment_type'];
            $ticketPayment->save();

            // Update Ticket data and save it to the database
            $ticketUpdate = Ticket::where('ticket_id', $validatedData['ticket_id'])->first();
            $ticketUpdate->status = 'pendingPayment';
            $ticketUpdate->steps = 2;
            $ticketUpdate->save();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Product for payment created successfully.');
        } catch (\Exception $e) {
            // Handle exceptions (log, rollback, etc.)
            DB::rollback();
            Log::error('Error in initialPayment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    



    
}
