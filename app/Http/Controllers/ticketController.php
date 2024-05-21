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
        
            return view('tickets.assign-ticket', ['customer' => $customer, 'ticket_id' => $ticket->ticket_id]);
            
        } catch (\Throwable $e) {

            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving ticket id: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return back()->with(['error' => 'Error Saving Ticket ID']);

        } 
    }
    
    
    
}
