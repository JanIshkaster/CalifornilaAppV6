<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class DeclaredProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_name', 
        'product_link', 
        'product_qty', 
        'product_variant',
        'shipping_method',
        'request_method'
    ]; 

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function Ticket(){
        return $this->hasOne(Ticket::class);
    }

}
