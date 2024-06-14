<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ticketNotes;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\ticketAdditionalFees;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;

class ticketAddFeeController extends Controller
{
    //Store the ticket additional fee to database
    public function store_add_fee(Request $request){    
        
        // Validate the request
        $request->validate([
            'amount' => 'required|integer',
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'fee_data_details' => 'required|string'
        ]);
    
        // Get data request
        $data = $request->all(); 
    
        // Create a new ticket Note and save it to the database
        $ticketAdditionalFees = new ticketAdditionalFees;
        $ticketAdditionalFees->ticket_id = $data['ticket_id']; 
        $ticketAdditionalFees->amount = $data['amount'];  
        $ticketAdditionalFees->fee_data_details = $data['fee_data_details'];  
        $ticketAdditionalFees->save(); // Save the data
    
        // After saving the ticket additional fee, send the mail
        $getCustomerIdFromTicket = Ticket::where('ticket_id', $data['ticket_id'])->get(); // Get Customer ID form Ticket
        $getCustomer = Customer::where('id', $getCustomerIdFromTicket->first()->customer_id)->get(); // Replace with the customer's email address  
        $customerEmail = $getCustomer->first()->email;
        Mail::to($customerEmail)->send(new sendMail($data));
    
        return redirect()->back()->with('success', 'Fee added successfully');
    }
}
