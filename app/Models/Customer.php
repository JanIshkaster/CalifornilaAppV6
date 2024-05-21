<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddedProduct;
use App\Models\customerAddress;
use App\Models\DeclaredProducts;
use App\Models\Ticket;
use App\Models\ticketNotes;
use App\Models\ticketProofOfPayment;
use App\Models\ticketAdditionalFees;
use App\Models\customerLogs;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'first_name', 
        'last_name', 
        'email', 
        'phone'
    ];

    public function DeclaredProducts(){
        return $this->hasMany(DeclaredProducts::class);
    }

    public function addedProducts(){
        return $this->hasMany(CustomerAddedProduct::class);
    }

    public function customerAddress(){
        return $this->hasOne(customerAddress::class);
    }

    public function Ticket(){
        return $this->hasMany(Ticket::class);
    } 

    public function customerLogs(){
        return $this->hasMany(customerLogs::class);
    }
 

}
