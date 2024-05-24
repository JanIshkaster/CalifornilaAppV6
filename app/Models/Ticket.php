<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\ticketNotes;
use App\Models\ticketProofOfPayment;
use App\Models\ticketAdditionalFees;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'ticket_id',
        'steps',
        'shipping_method',
        'status',
        'tracking' 
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function ticketNotes(){
        return $this->hasMany(ticketNotes::class, 'ticket_id', 'ticket_id');
    }

    public function ticketProofOfPayment(){
        return $this->hasMany(ticketProofOfPayment::class);
    }

    public function ticketAdditionalFees(){
        return $this->hasMany(ticketAdditionalFees::class);
    }

    public function DeclaredProducts(){
        return $this->belongsToMany(DeclaredProducts::class);
    }

}
