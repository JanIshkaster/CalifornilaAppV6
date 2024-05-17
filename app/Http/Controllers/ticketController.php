<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\customerAddress;
use App\Models\BuyingAssistanceProducts;
use Illuminate\Http\Request;

class ticketController extends Controller
{
    //Ticket Page
    public function ticket_index() {
        
        // Retrieve only customers who have buyingAssistanceProducts
        $customers = Customer::with('BuyingAssistanceProducts')->has('BuyingAssistanceProducts')->get();
    
        return view('tickets.ticket_index', ['customers' => $customers]);
    }
    
    

    //Ticket Page
    public function assign_ticket(){
        return view('tickets.assign-ticket');
    }
}
