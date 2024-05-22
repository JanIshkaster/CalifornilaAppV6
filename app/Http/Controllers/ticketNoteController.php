<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ticketNotes;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;


class ticketNoteController extends Controller
{
    //Store the ticket note to database
    public function store_ticket_note(Request $request){   
        
        // Validate the request
        $request->validate([
            'ticket_note' => 'required|string',
            'ticket_id' => 'required|exists:tickets,ticket_id'
        ]);
    
        // Get data request
        $data = $request->all();
    
        // Create a new ticket Note and save it to the database
        $ticketNote = new ticketNotes;
        $ticketNote->ticket_id = $data['ticket_id']; 
        $ticketNote->content = $data['ticket_note'];  
        $ticketNote->save(); // Save the data
    
        // After saving the note, send the mail
        $customerEmail = 'jan@ishkaster.com'; // Replace with the customer's email address
        Mail::to($customerEmail)->send(new sendMail($data));
    
        return redirect()->back()->with('success', 'Note added successfully');
    }
    
}
